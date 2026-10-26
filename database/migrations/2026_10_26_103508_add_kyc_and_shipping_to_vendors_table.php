<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // KYC & Identity Verification
            $table->string('id_document_type')->nullable()->after('verification_document');
            $table->string('id_number')->nullable()->after('id_document_type');
            $table->string('id_document_image')->nullable()->after('id_number');
            $table->string('business_certificate_document')->nullable()->after('id_document_image');

            // Shipping & Warehouse Logistics
            $table->text('pickup_address')->nullable()->after('business_certificate_document');
            $table->string('pickup_city')->nullable()->after('pickup_address');
            $table->string('pickup_state')->nullable()->after('pickup_city');
            $table->string('pickup_pincode')->nullable()->after('pickup_state');
            $table->string('support_email')->nullable()->after('pickup_pincode');
            $table->string('alternate_phone')->nullable()->after('support_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn([
                'id_document_type',
                'id_number',
                'id_document_image',
                'business_certificate_document',
                'pickup_address',
                'pickup_city',
                'pickup_state',
                'pickup_pincode',
                'support_email',
                'alternate_phone',
            ]);
        });
    }
};
