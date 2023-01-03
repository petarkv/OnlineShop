$(document).ready(function(){
    // $("#sort").on('change',function(){
    //     this.form.submit();
    // });

    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    // PRODUCT FILTERS
    $("#sort").on('change',function(){
        var sort = $(this).val();
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var pattern = get_filter("pattern");
        var fit = get_filter("fit");
        var occasion = get_filter("occasion");
        var url = $("#url").val();
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,pattern:pattern,fit:fit,occasion:occasion,sort:sort,url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        })
    });

    $(".fabric").on('click',function(){
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var pattern = get_filter("pattern");
        var fit = get_filter("fit");
        var occasion = get_filter("occasion");
        var sort = $("#sort option:selected").val();
        var url = $("#url").val();
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,pattern:pattern,fit:fit,occasion:occasion,sort:sort,url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        })
    });

    $(".sleeve").on('click',function(){
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var pattern = get_filter("pattern");
        var fit = get_filter("fit");
        var occasion = get_filter("occasion");
        var sort = $("#sort option:selected").val();
        var url = $("#url").val();
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,pattern:pattern,fit:fit,occasion:occasion,sort:sort,url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        })
    });

    $(".pattern").on('click',function(){
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var pattern = get_filter("pattern");
        var fit = get_filter("fit");
        var occasion = get_filter("occasion");
        var sort = $("#sort option:selected").val();
        var url = $("#url").val();
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,pattern:pattern,fit:fit,occasion:occasion,sort:sort,url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        })
    });

    $(".fit").on('click',function(){
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var pattern = get_filter("pattern");
        var fit = get_filter("fit");
        var occasion = get_filter("occasion");
        var sort = $("#sort option:selected").val();
        var url = $("#url").val();
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,pattern:pattern,fit:fit,occasion:occasion,sort:sort,url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        })
    });

    $(".occasion").on('click',function(){
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var pattern = get_filter("pattern");
        var fit = get_filter("fit");
        var occasion = get_filter("occasion");
        var sort = $("#sort option:selected").val();
        var url = $("#url").val();
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,pattern:pattern,fit:fit,occasion:occasion,sort:sort,url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        })
    });

    function get_filter(class_name) {
        var filter = [];
        $('.'+class_name+':checked').each(function(){
            filter.push($(this).val());
        });
        return filter;
    }

    $("#getPrice").change(function () {
        var size = $(this).val();
        if (size=="") {
            alert("Please select Size");
            return false;
        }
        var product_id = $(this).attr("product-id");
        // alert(product_id);
        $.ajax({
            url:'/get-product-price',
            data:{size:size,product_id:product_id},
            type:'post',
            success:function(resp){
                // alert(resp['product_price']); 
                // alert(resp['discounted_price']);
                // return false;
                if (resp['discount']>0) {
                    $(".getAttrPrice").html("<del>EUR "+resp['product_price']+"</del> EUR "+"<font color=red>"+resp['final_price']+"</font>");
                }else{
                    $(".getAttrPrice").html("EUR "+resp['product_price']);
                }
                // $(".getAttrPrice").html("EUR "+resp);
            },error:function(){
                alert("Greska bato");
            }
        });
    });

    // UPDATE CART ITEMS
    $(document).on('click','.btnItemUpdate',function(){
        // If button MINUS gets clicked
        if($(this).hasClass('qtyMinus')){
            var quantity = $(this).prev().val();
            // alert(quantity);
            if (quantity<=1) {
                alert("Item Quantity must be 1 or greater!");
                return false;
            }else{
                new_qty = parseInt(quantity)-1;
            }
        }
        // If button PLUS gets clicked
        if ($(this).hasClass('qtyPlus')) {
            var quantity = $(this).prev().prev().val();
            // alert(quantity); return false;
            new_qty = parseInt(quantity)+1;            
        }
        // alert(new_qty);
        var cartid = $(this).data('cartid');
        // alert(cartid);
        $.ajax({
            data:{"cartid":cartid,"qty":new_qty},
            url:'/update-cart-item-qty',
            type:'post',
            success:function(resp){
                // alert(resp);
                // alert(resp.status);
                if (resp.status==false) {
                    alert(resp.message);
                }
                // alert(resp.totalCartItems);
                $(".totalCartItems").html(resp.totalCartItems);
                $("#AppendCartItems").html(resp.view);
            },error:function(){
                alert("Nema povecanja");
            }
        });
    });

    // DELETE CART ITEMS
    $(document).on('click','.btnItemDelete',function(){        
        var cartid = $(this).data('cartid');
        // alert(cartid); return false;
        var result = confirm("Want to delete this Cart Item?");
        if (result) {
            $.ajax({
                data:{"cartid":cartid},
                url:'/delete-cart-item',
                type:'post',
                success:function(resp){
                    $(".totalCartItems").html(resp.totalCartItems);                
                    $("#AppendCartItems").html(resp.view);
                },error:function(){
                    alert("Nema brisanja");
                }
            });    
        }        
    });

    // Validate Register Form on keyup and submit
    $("#registerForm").validate({
        rules: {
            name: "required",
            email: {
                required: true,
                email: true,
                remote: "/check-email"
            },
            mobile: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
        },
        messages: {
            name: "Please enter your Name",
            email: {
                required: "Please enter email address",
                email: "Please enter a valid email address",
                remote: "Email already exists"
            },
            mobile: {
                required: "Please enter your Mobile",
                minlength: "Mobile min length is 10 digits",
                maxlength: "Mobile max length is 10 digits",
                digits: "Enter only digits"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long",
                maxlength: "Your password must be maximun 20 characters long"
            },
        }
    });

    // Validate Login Form on keyup and submit
    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
        },
        messages: {
            email: {
                required: "Please enter email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please enter your password",
                minlength: "Your password must be at least 6 characters long",
                maxlength: "Your password must be maximum 20 characters long"
            },
        }
    });

    // Validate Account Form on keyup and submit
    $("#accountForm").validate({
        rules: {
            name: {
                required: true,
                lettersonly: true
            },            
            mobile: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true
            },            
        },
        messages: {
            name: {
                required: "Please enter your Name",
                lettersonly: "Please enter valid Name"
            },            
            mobile: {
                required: "Please enter your Mobile",
                minlength: "Mobile min length is 10 digits",
                maxlength: "Mobile max length is 10 digits",
                digits: "Enter only digits"
            },            
        }
    });

    // Validate Password Form - Confirm Password
    $("#passwordForm").validate({
        rules: {
            current_password: {
                required: true,
                minlength: 6,
                maxlength: 20
            },            
            new_password: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            confirm_password: {
                required: true,
                minlength: 6,
                maxlength: 20,
                equalTo:"#new_password"
            },                        
        },
        messages: {
            current_password: {
                required: "Please enter your Current Password",
                minlength: "Current Password min length is 6 digits",
                maxlength: "Current Password max length is 20 digits",
            },
            new_password: {
                required: "Please enter your New Password",
                minlength: "New Password min length is 6 digits",
                maxlength: "New Password max length is 20 digits",
            },
            confirm_password: {
                required: "Please enter your Confirm Password",
                minlength: "Confirm Password min length is 6 digits",
                maxlength: "Confirm Password max length is 20 digits",
                equalTo: "Passwords are not equals",
            },
        }
    });

    // Check User Current Password
    $("#current_password").keyup(function() {
       var current_password = $(this).val();
       //alert(current_password);
       $.ajax({
            type:'post',
            url:'/check-user-password',
            data:{current_password:current_password},
            success:function(resp){
                // alert(resp);
                if (resp=="false") {
                    $("#checkPassword").html("<font color='red'>Current Password is Incorrect</font>");
                }else if (resp=="true") {
                    $("#checkPassword").html("<font color='green'>Current Password is Correct</font>");
                }
            },error:function(){
                alert("Nije Current Pwd");
            }
       });
    });

    // Apply Coupon
    $("#applyCoupon").submit(function(){
        // alert("Snizenje");
        var user = $(this).attr("user");
        if (user==1) {
            // do nothing
        }else{
            alert("Please login to apply Coupon");
            return false;
        }
        var code = $("#code").val();
        $.ajax({
            type:'post',
            data:{code:code},
            url:'/apply-coupon',
            success:function(resp){
                if (resp.message!="") {
                    alert(resp.message);
                }
                $(".totalCartItems").html(resp.totalCartItems);
                $("#AppendCartItems").html(resp.view);
            },error:function(){
                alert("Greska Kupon");
            }
        });
    });
});