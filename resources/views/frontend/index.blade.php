@extends('frontend.frontend_master')
@section('frontend_content')
<!-- banner slider start  -->
@include('frontend.home.sliderbanner')
<!-- banner slider end  -->

         <!-- flash selas start  -->

@include('frontend.home.flashsales')
         <!-- flash selas end  -->

         <!-- flash selas start  -->
<!-- flash selas end  -->

<!-- Update product category section -->
@include('frontend.home.topcategory')

<!-- product start  -->
@include('frontend.home.newarrivelproducts')

<!-- product end  -->

<!-- Add more product items with the same pattern -->
<!-- promot banner start  -->
@include('frontend.home.adsbanner')
<!-- promot banner end  -->

   <!-- product start  -->
@include('frontend.home.newcollectionproducts')
<!-- product end  -->
<!-- show more product  start  -->
<div class="max-w-7xl mx-auto px-4 py-2">
    <div class="flex items-center justify-center space-x-4">
        <a href="#" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors">Show More</a>
    </div>
</div>
<!-- show more product  end  -->

@endsection