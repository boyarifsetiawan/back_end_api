<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //     final String productId;
    //   final String productTitle;
    //   final int productQuantity;
    //   final String productColor;
    //   final String productSize;
    //   final double productPrice;
    //   final double totalPrice;
    //   final String productImage;
    //   final String createdDate;
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_title');
            $table->unsignedInteger('product_quantity');
            $table->string('product_color');
            $table->string('product_size');
            $table->decimal('product_price', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->string('product_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
