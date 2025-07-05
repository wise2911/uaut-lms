<?php

     namespace App\Http\Controllers;

     use App\Models\Course;
     use App\Models\Payment;
     use Illuminate\Http\Request;
     use Illuminate\Support\Facades\Auth;
     use Illuminate\Support\Facades\Log;
     use Srmklive\PayPal\Services\PayPal as PayPalClient;
     use GuzzleHttp\Client as GuzzleClient;

     class UserHomeController extends Controller
     {
         public function index()
         {
             $courses = Course::with(['video', 'ratings'])->paginate(9);
             return view('courses.index', compact('courses'));
         }

         public function enroll(Request $request, Course $course)
         {
             if (!Auth::check()) {
                 return redirect()->route('login')->with('error', 'Please login to enroll in the course.');
             }

             if (Auth::user()->courses()->where('course_id', $course->id)->exists()) {
                 return redirect()->route('courses.learn', $course)->with('success', 'You are already enrolled in this course.');
             }

             if (empty($course->price) || $course->price <= 0) {
                 Log::error('Invalid course price', ['course_id' => $course->id, 'price' => $course->price]);
                 return redirect()->route('courses.show', $course)->with('error', 'This course cannot be enrolled at this time due to an invalid price. Please contact support.');
             }

             try {
                 $provider = new PayPalClient;
                 $provider->setApiCredentials(config('paypal'));
                 $provider->setClient(new GuzzleClient(['verify' => false])); // Disable SSL verification for local testing
                 $accessToken = $provider->getAccessToken();
                 Log::info('PayPal Access Token', ['token' => $accessToken]);

                 $order = $provider->createOrder([
                     'intent' => 'CAPTURE',
                     'purchase_units' => [
                         [
                             'amount' => [
                                 'currency_code' => 'USD',
                                 'value' => number_format($course->price, 2, '.', ''),
                             ],
                             'description' => 'Enrollment for course: ' . $course->title,
                         ],
                     ],
                     'application_context' => [
                         'return_url' => route('courses.payment.success', $course),
                         'cancel_url' => route('courses.payment.cancel', $course),
                     ],
                 ]);

                 Log::info('PayPal Order Response', ['order' => $order]);

                 if (isset($order['id']) && $order['status'] === 'CREATED') {
                     $payment = Payment::create([
                         'user_id' => Auth::id(),
                         'course_id' => $course->id,
                         'amount' => $course->price,
                         'paypal_order_id' => $order['id'],
                         'status' => 'PENDING',
                     ]);

                     foreach ($order['links'] as $link) {
                         if ($link['rel'] === 'approve') {
                             return redirect()->away($link['href']);
                         }
                     }
                 }

                 Log::error('PayPal order creation failed', ['order' => $order]);
                 return redirect()->route('courses.show', $course)->with('error', 'Failed to initiate payment. Please try again.');
             } catch (\Exception $e) {
                 Log::error('PayPal enrollment error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                 return redirect()->route('courses.show', $course)->with('error', 'An error occurred during payment. Please try again.');
             }
         }

         public function enrollSuccess(Request $request, Course $course)
         {
             try {
                 $provider = new PayPalClient;
                 $provider->setApiCredentials(config('paypal'));
                 $provider->setClient(new GuzzleClient(['verify' => false])); // Disable SSL verification for local testing
                 $accessToken = $provider->getAccessToken();

                 $orderId = $request->query('token');
                 $capture = $provider->capturePaymentOrder($orderId);

                 Log::info('PayPal Capture Response', ['capture' => $capture, 'order_id' => $orderId]);

                 if (isset($capture['status']) && $capture['status'] === 'COMPLETED') {
                     $payment = Payment::where('paypal_order_id', $orderId)->firstOrFail();
                     $payment->update([
                         'status' => 'COMPLETED',
                         'paypal_transaction_id' => $capture['id'],
                     ]);

                     Auth::user()->courses()->attach($course->id, ['progress' => 0]);

                     return redirect()->route('courses.learn', $course)->with('success', 'Enrollment successful! You can now access the course.');
                 }

                 Log::error('PayPal capture failed', ['capture' => $capture, 'order_id' => $orderId]);
                 return redirect()->route('courses.show', $course)->with('error', 'Payment capture failed: ' . ($capture['error']['message'] ?? 'Unknown error. Please try again.'));
             } catch (\Exception $e) {
                 Log::error('PayPal capture error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'order_id' => $request->query('token')]);
                 return redirect()->route('courses.show', $course)->with('error', 'An error occurred during payment capture: ' . $e->getMessage());
             }
         }

         public function enrollCancel(Course $course)
         {
             return redirect()->route('courses.show', $course)->with('error', 'Payment was cancelled.');
         }
     }