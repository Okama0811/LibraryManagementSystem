<div id="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12 all-product" id="prdCtn">
				<h3 class="content-menu">
					<?php echo $title; ?>
				</h3>
				<?php
				foreach ($books as $book) { ?>
					<div class='product-container' onclick="Display_BookDetail('<?php echo $book['book_id'] ?>')">
						<a data-toggle='modal' href='product/PrdDetail/<?php echo $book['book_id'] ?>' data-target='#modal-id'>
							<div style="text-align: center;" class='product-img'>
								<img src='uploads/covers/<?php echo $book['cover_image'] ?>'>
							</div>
							<div class='product-info'>
							<h4><b><?php echo $book['title']; ?></b></h4>
								<div class='buy'>
									<a class='btn btn-primary btn-md cart-container <?php
									if(isset($_SESSION['cart'])){
										if(array_search($book['book_id'], $_SESSION['cart']) !== false){
											echo 'cart-ordered';
										}
									} ?>' data-masp='<?php echo $book['book_id'] ?>' >
									<i title='Thêm vào giỏ hàng' class='glyphicon glyphicon-shopping-cart cart-item'></i>
								</a>
								<a href="client/buynow/<?php echo $book['book_id'] ?>" class="snip0050"><span>Mua ngay</span><i class="glyphicon glyphicon-ok"></i>
								</a>
							</div>
						</div>
					</a>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>	
</div>
<div class="container-fluid text-center" style="padding: 15px;">
	<div class="row">
		<div class="col-sm-12">
			<button id="loadmoreBtn" onclick="loadmore(8)" class="snip1582">Load more</button>
		</div>
	</div>
</div>
<script type="text/javascript">
var cr = $('#contentTitle').data('type');
switch(cr){
	case 'newest':
		$('#spm').css('background-color','white');
		break;
	case 'bestselling':
		$('#mntq').css('background-color','white');
		break;
	}

function Display_BookDetail(book_id){
	$('#modal-id').attr('data-remote','index.php?model=book&action=show&id='+book_id);
	$('#modal-book').empty();

	$.ajax({
		url : "index.php?model=book&action=show&id="+book_id,
		type : "post",
		dataType:"text",
		data : {
			book_id
		},
		success : function (result){
		$('#modal-book').html(result);
		}
	});
}

function loadmore(start){
	var next = start + 8;
	var q = $('#srch-val').val();
	$('#loadmoreBtn').attr('onclick','loadmore('+next+')');
	var type = $('#contentTitle').data('type');
	$.ajax({
		url : "index.php?model=book&action=loadmore",
		type : "get",
		dataType:"text",
		data : {
			start, 
			type,
			q
		},
		success : function (result){
			if(!result){alert("Đã hết sản phẩm để hiển thị!"); return 0;}
			$('#prdCtn').append(result);
		}
	});
}
</script>