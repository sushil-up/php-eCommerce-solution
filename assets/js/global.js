jQuery(document).ready(function(){
	
	updateCartCounter()
	
	function isJson(str) {
	    try {
	        JSON.parse(str);
	    } catch (e) {
	        return false;
	    }
	    return true;
	}

	function initToast(status, message="Title"){
		Swal.fire({
		    toast: true,
		    animation: true,
	    	title: message,
	    	icon: status,
		    position: 'top-right',
		    showConfirmButton: false,
		    timer: 3000,
		    timerProgressBar: true
	  	});
	}
	function updateCartCounter(){
		$.ajax({
			url: BASE_URL+"/cart-count",
			type: 'get',
			success : function(resp){
				let respObj = JSON.parse( resp );
				jQuery('.cart-ico').find('.cart-counter').text(respObj.message)
			}
		})
	}

	$('#testimonialsCarousel').slick({
        dots: true,
        infinite: true,
        speed: 350,
        slidesToShow: 1,
        adaptiveHeight: true
    });

    function confirmBox(ajaxUrl){
	    Swal.fire({
	        title: "Are you sure?",
	        icon: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Yes, delete it!",
	        cancelButtonText: "No, cancel!",
	        reverseButtons: true
	    }).then(function(result) {
	        if (result.value) {
				$.ajax({
					url: ajaxUrl,
					type: 'post',
					success : function(resp){
						let respObj = JSON.parse( resp );
						updateCartCounter();
						initToast(respObj.status, respObj.message);
                        setTimeout(function(){ location.reload(); }, 2000);
					}
				})

	        }
	    });
    }

   jQuery(document).on('click', '.clearData', function(event){
		event.preventDefault();
		let url = $(this).attr('href');
		confirmBox(url);
	});

   jQuery(document).on('click', '.deleteCart', function(){
		let thisObj = $(this);
		setTimeout(function(){ 
			thisObj.closest('.row').next('hr').fadeOut( 500, function() { $(this).remove(); });
			thisObj.closest('.row').fadeOut( 500, function() { $(this).remove(); });
		}, 200);

	});

	jQuery(document).on('click', '.wishlistItem', function(){
		let thisObj = jQuery(this);
		setTimeout(function(){ 
			if( thisObj.hasClass('response-success') ){
				thisObj.find('i').toggleClass('fa-heart-o');
				thisObj.find('i').toggleClass('fa-heart');
			}
		}, 300);
	});

	jQuery(document).on('click', '.deleteRecord', function(event){
		event.preventDefault();
		let thisObj = jQuery(this);
		let ajaxUrl = thisObj.attr('href');
		if( ajaxUrl == '' ) ajaxUrl = thisObj.attr('data-href');
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
              
				$.ajax({
					url: ajaxUrl,
					type: 'post',
					success : function(resp){
						let respObj = JSON.parse( resp );
						updateCartCounter();
						initToast(respObj.status, respObj.message);
                        setTimeout(function(){ location.reload(); }, 2000);
					}
				})
   			}
		});
	})

	jQuery(document).on('click', '.activeAjax', function(event){
		event.preventDefault();
		let thisObj = $(this);
		let ajaxUrl = thisObj.attr('href');
		if( ajaxUrl == '' ) ajaxUrl = thisObj.attr('data-href');
		if( ajaxUrl != ''){
			$.ajax({
				url: ajaxUrl,
				type: 'post',
				success : function(resp){
					thisObj.removeClass('response-success');
					thisObj.removeClass('response-error');
					if( isJson(resp) ){
						let respObj = JSON.parse( resp );
						initToast(respObj.status, respObj.message);
						thisObj.addClass('response-'+respObj.status);
				  	}else{
						thisObj.addClass('response-error');
						initToast('error', 'Something went wrong!');
				  	}
				}
			})
			updateCartCounter();
		}
	}) // Ajax Click Event End

})