$(document).ready(function(){
    // Check Admin Password is correct or not
    $("#current_password").keyup(function(){
        var current_password = $("#current_password").val();
        // alert(current_password);
        $.ajax({
            type:'post',
            url:'/admin/check-current-password',
            data:{current_password:current_password},
            success:function(resp){
            //   alert(resp);
              if (resp=="Not Same Pwds") {
                  $("#checkCurrentPassword").html("<font color=red>Current Password is incorrect</font>")
              }else if (resp=="Same Pwds") {
                $("#checkCurrentPassword").html("<font color=green>Current Password is correct</font>")
              }  
            },error:function(){
                alert("ERROR");
            },
        });
    });

    // Update Section Status
    // $(".updateSectionStatus").click(function(){
    $(document).on("click",".updateSectionStatus",function(){
        var status = $(this).text();
        var section_id = $(this).attr("section_id");
        // alert(status);
        // alert(section_id);
        $.ajax({            
           type:'post',
           url:'/admin/update-section-status',
           data:{status:status,section_id:section_id},
           success:function(resp){
                // alert(resp['status']);
                // alert(resp['section_id']);
                if(resp['status']==0){
                    $("#section-"+section_id).html("<a class='updateSectionStatus' href='javascript:void(0)'>Inactive</a>"); 
                }else if(resp['status']==1){
                    $("#section-"+section_id).html("<a class='updateSectionStatus' href='javascript:void(0)'>Active</a>"); 
                }
           },error:function(){
               alert("GreskaBRE!");
           } 
        });
    });

    // Update Brand Status
    // $(".updateBrandStatus").click(function(){
    $(document).on("click",".updateBrandStatus",function(){
        var status = $(this).children("i").attr("status");
        // alert(status); return false;
        var brand_id = $(this).attr("brand_id");
        // alert(status);
        // alert(brand_id);
        $.ajax({            
           type:'post',
           url:'/admin/update-brand-status',
           data:{status:status,brand_id:brand_id},
           success:function(resp){
                // alert(resp['status']);
                // alert(resp['brand_id']);
                if(resp['status']==0){
                    $("#brand-"+brand_id).html("<i class='fas fa-toggle-off' status='Inactive'></i>"); 
                }else if(resp['status']==1){
                    $("#brand-"+brand_id).html("<i class='fas fa-toggle-on' status='Active'></i>"); 
                }
           },error:function(){
               alert("GreskaBRE!");
           } 
        });
    });

    // Update Banner Status
    // $(".updateBannerStatus").click(function(){
        $(document).on("click",".updateBannerStatus",function(){
            var status = $(this).children("i").attr("status");
            // alert(status); return false;
            var banner_id = $(this).attr("banner_id");
            // alert(status);
            // alert(banner_id);
            $.ajax({            
               type:'post',
               url:'/admin/update-banner-status',
               data:{status:status,banner_id:banner_id},
               success:function(resp){
                    // alert(resp['status']);
                    // alert(resp['banner_id']);
                    if(resp['status']==0){
                        $("#banner-"+banner_id).html("<i class='fas fa-toggle-off' status='Inactive'></i>"); 
                    }else if(resp['status']==1){
                        $("#banner-"+banner_id).html("<i class='fas fa-toggle-on' status='Active'></i>"); 
                    }
               },error:function(){
                   alert("GreskaBRE!");
               } 
            });
        });

    // Update Category Status
    // $(".updateCategoryStatus").click(function(){
    $(document).on("click",".updateCategoryStatus",function(){
        var status = $(this).children("i").attr("status");
        var category_id = $(this).attr("category_id");
        // alert(status);
        // alert(category_id);
        $.ajax({            
           type:'post',
           url:'/admin/update-category-status',
           data:{status:status,category_id:category_id},
           success:function(resp){
                // alert(resp['status']);
                // alert(resp['category_id']);
                if(resp['status']==0){
                    $("#category-"+category_id).html("<i class='fas fa-toggle-off' status='Inactive'></i>"); 
                }else if(resp['status']==1){
                    $("#category-"+category_id).html("<i class='fas fa-toggle-on' status='Active'></i>"); 
                }
           },error:function(){
               alert("GreskaBRE!");
           } 
        });
    });

    // Update Coupon Status
    $(document).on("click",".updateCouponStatus",function(){
        var status = $(this).children("i").attr("status");
        // alert(status); return false;
        var coupon_id = $(this).attr("coupon_id");
        // alert(status);
        // alert(coupon_id);
        $.ajax({            
            type:'post',
            url:'/admin/update-coupon-status',
            data:{status:status,coupon_id:coupon_id},
            success:function(resp){
                // alert(resp['status']);
                // alert(resp['coupon_id']);
                if(resp['status']==0){
                    $("#coupon-"+coupon_id).html("<i class='fas fa-toggle-off' status='Inactive'></i>"); 
                }else if(resp['status']==1){
                    $("#coupon-"+coupon_id).html("<i class='fas fa-toggle-on' status='Active'></i>"); 
                }
            },error:function(){
                alert("GreskaBRE!");
            } 
        });
    });

    // Append Categories Level
    $('#section_id').change(function(){
        var section_id = $(this).val();
        // alert(section_id);
        $.ajax({
            type:'post',
            url:'/admin/append-categories-level',
            data:{section_id:section_id},
            success:function(resp){
                $("#appendCategoriesLevel").html(resp);
            },error:function(){
                alert("GreskaBre!");
            }
        });
    });

    // Confirm Deleting Category
    /* $(".confirmDelete").click(function(){
        var name = $(this).attr("name");
        if (confirm("Are You sure to delete this "+name+"?")) {
            return true;
        }
            return false;
    }); */

    // Confirm Deleting Category with SweetAlert
    // $(".confirmDelete").click(function(){
    $(document).on("click",".confirmDelete",function(){
        var record = $(this).attr("record");
        var recordid = $(this).attr("recordid");
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
            //   Swal.fire(
            //     'Deleted!',
            //     'Your file has been deleted.',
            //     'success'
            //   )
              window.location.href="/admin/delete-"+record+"/"+recordid;
            }
        });
    });

    // Update Product Status
    // $(".updateProductStatus").click(function(){
    $(document).on("click",".updateProductStatus",function(){
        var status = $(this).children("i").attr("status");
        var product_id = $(this).attr("product_id");
        // alert(status);
        // alert(product_id);
        $.ajax({            
           type:'post',
           url:'/admin/update-product-status',
           data:{status:status,product_id:product_id},
           success:function(resp){
                // alert(resp['status']);
                // alert(resp['product_id']);
                if(resp['status']==0){
                    $("#product-"+product_id).html("<i class='fas fa-toggle-off' status='Inactive'></i>"); 
                }else if(resp['status']==1){
                    $("#product-"+product_id).html("<i class='fas fa-toggle-on' status='Active'></i>"); 
                }
           },error:function(){
               alert("GreskaBRE!");
           } 
        });
    });

    // Update Attribute Status
    // $(".updateAttributeStatus").click(function(){
    $(document).on("click",".updateAttributeStatus",function(){
        var status = $(this).text();
        var attribute_id = $(this).attr("attribute_id");
        // alert(status);
        // alert(attribute_id);
        $.ajax({            
           type:'post',
           url:'/admin/update-attribute-status',
           data:{status:status,attribute_id:attribute_id},
           success:function(resp){
                // alert(resp['status']);
                // alert(resp['attribute_id']);
                if(resp['status']==0){
                    $("#attribute-"+attribute_id).html("Inactive"); 
                }else if(resp['status']==1){
                    $("#attribute-"+attribute_id).html("Active"); 
                }
           },error:function(){
               alert("GreskaBRE!");
           } 
        });
    });

    // Update Image Status
    // $(".updateImageStatus").click(function(){
    $(document).on("click",".updateImageStatus",function(){
        var status = $(this).text();
        var image_id = $(this).attr("image_id");
        // alert(status);
        // alert(image_id);
        $.ajax({            
           type:'post',
           url:'/admin/update-image-status',
           data:{status:status,image_id:image_id},
           success:function(resp){
                // alert(resp['status']);
                // alert(resp['image_id']);
                if(resp['status']==0){
                    $("#image-"+image_id).html("Inactive"); 
                }else if(resp['status']==1){
                    $("#image-"+image_id).html("Active"); 
                }
           },error:function(){
               alert("GreskaBRE!");
           } 
        });
    });

    // Show/Hide Coupon Code Field for Manual/Automatic
    $("#manualCoupon").click(function(){
        $("#couponCodeField").show();
    });

    $("#automaticCoupon").click(function(){
        $("#couponCodeField").hide();
    });


    // Products Attributes Add/Remove Fields
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div style="margin-top:10px;"><input type="text" name="size[]" placeholder="Size" style="width:120px;"/><input type="text" name="sku[]" placeholder="SKU" style="width:120px; margin-left:3px;"/><input type="text" name="price[]" placeholder="Price" style="width:120px; margin-left:3px;"/><input type="text" name="stock[]" placeholder="Stock" style="width:120px; margin-left:3px;"/><a href="javascript:void(0);" class="remove_button"> <i class="fas fa-minus"></i></a></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });

});