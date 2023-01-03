<?php use App\Product; ?>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Product</th>
        <th colspan="2">Description</th>
        <th>Quantity/Update</th>
        <th>Unit Price</th>
        <th>Category/Product Discount</th>
        <th>Sub Total</th>
    </tr>
    </thead>
    <tbody>
        <?php $total_price = 0; ?>
        @foreach($userCartItems as $item)
        {{-- <?php $attrPrice = Cart::getProductAttrPrice($item['product_id'],$item['size']); ?> --}}
        <?php $attrPrice = Product::getDiscountedAttrPrice($item['product_id'],$item['size']); ?>
    <tr>
        <td> <img width="60" src="{{ asset('images/product_images/small/'.$item['product']['main_image']) }}" alt=""/></td>
        <td>{{ $item['product']['product_name'] }}<br/>Code : {{ $item['product']['product_code'] }}
        <br/>Color : {{ $item['product']['product_color'] }}<br/>Size : {{ $item['size'] }}</td>
        <td colspan="2">
        <div class="input-append"><input class="span1" style="max-width:34px" placeholder="1" 
            id="appendedInputButtons" size="16" type="text" value="{{ $item['quantity'] }}">
            <button class="btn btnItemUpdate qtyMinus" type="button" data-cartid="{{ $item['id'] }}"><i class="icon-minus"></i></button>
            <button class="btn btnItemUpdate qtyPlus" type="button" data-cartid="{{ $item['id'] }}"><i class="icon-plus"></i></button>
            <button class="btn btn-danger btnItemDelete" type="button" data-cartid="{{ $item['id'] }}"><i class="icon-remove icon-white"></i></button>				
        </div>
        </td>
        <td>EUR {{ $attrPrice['product_price'] }}</td>
        <td>EUR {{ $attrPrice['discount'] }}</td>
        <td>EUR &nbsp;<strong> {{ $attrPrice['final_price'] * $item['quantity'] }} </strong></td>
    </tr>
    <?php $total_price = $total_price + ($attrPrice['final_price'] * $item['quantity']); ?>
    @endforeach
    
    <tr>
        <td colspan="6" style="text-align:right">Sub Total Price:	</td>
        <td> EUR &nbsp;{{ $total_price }}</td>
    </tr>
        <tr>
        <td colspan="6" style="text-align:right">Coupon Discount:	</td>
        <td> EUR &nbsp;0.00</td>
    </tr>
        <tr>
        <td colspan="6" style="text-align:right">Total Tax:	</td>
        <td> EUR &nbsp;{{ $total_price * 0.2 }}</td>
    </tr>
        <tr>
        <td colspan="6" style="text-align:right"><strong>GRAND TOTAL (EUR {{ $total_price }} - EUR 0 + EUR {{ $total_price * 0.2 }}) =</strong></td>
        <td class="label label-important" style="display:block"> <strong> EUR &nbsp;{{ $total_price + $total_price * 0.2 }} </strong></td>
    </tr>
    </tbody>
</table>