<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPaymentsTablePaymentId extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop payment_id if it exists
            if (Schema::hasColumn('payments', 'payment_id')) {
                $table->dropColumn('payment_id');
            }
            // Ensure id is the primary key
            if (!Schema::hasColumn('payments', 'id')) {
                $table->id()->first();
            }
            // Ensure other columns are correct
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->change();
            $table->foreignId('course_id')->constrained()->onDelete('cascade')->change();
            $table->decimal('amount', 8, 2)->change();
            $table->string('paypal_order_id')->nullable()->change();
            $table->string('paypal_transaction_id')->nullable()->change();
            $table->string('status')->default('PENDING')->change();
            $table->timestamps()->change();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert to original payment_id if needed
            if (!Schema::hasColumn('payments', 'payment_id')) {
                $table->bigIncrements('payment_id')->first();
            }
            if (Schema::hasColumn('payments', 'id')) {
                $table->dropColumn('id');
            }
        });
    }
}