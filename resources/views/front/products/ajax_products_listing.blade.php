<?php use App\Product; ?>
<div class="tab-pane  active" id="blockView">
    <ul class="thumbnails">
        @foreach($categoryProducts as $product)
            <li class="span3">
                <div class="thumbnail" style="height: 400px;">
                    <a href="{{ url('product/'.$product['id']) }}">
                        <?php $product_image_path = 'images/product_images/small/'.$product['main_image'] ?>
                        @if (!empty($product['main_image']) && file_exists($product_image_path))
                            <img style="width: 150px;" src="{{ asset('images/product_images/small/'.$product['main_image']) }}" alt="">
                        @else
                            <img style="width: 175px;" src="{{ asset('images/product_images/no-image.png') }}" alt="">
                        @endif
                    </a>
                    <div class="caption">
                        <h5>{{ $product['product_name'] }} {{ $product['id'] }}</h5>
                        <p>
                            {{ $product['brand']['name'] }}
                        </p>
                        <?php $discounted_price = Product::getDiscountedPrice($product['id']); ?>
                        <h4 style="text-align:center"><a class="btn" href="{{ url('product/'.$product['id']) }}"> 
                            <i class="icon-zoom-in"></i></a> <a class="btn" href="#">Add to <i class="icon-shopping-cart"></i></a> 
                            <a class="btn btn-primary" href="#">
                                @if($discounted_price>0)
                                    <del>EUR {{ $product['product_price'] }}</del>
                                @else
                                    EUR {{ $product['product_price'] }}
                                @endif
                            </a></h4>
                            @if($discounted_price>0)
                                <h4><font color="red">Discounted Price: {{ $discounted_price }}</font></h4>
                            @endif
                            {{-- <p>
                                {{ $product['fabric'] }}
                            </p>
                            <p>
                                {{ $product['sleeve'] }}
                            </p>
                            <p>
                                {{ $product['pattern'] }}
                            </p>
                            <p>
                                {{ $product['fit'] }}
                            </p>
                            <p>
                                {{ $product['occasion'] }}
                            </p> --}}
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    <hr class="soft"/>
</div>