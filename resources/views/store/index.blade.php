<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Melbis Demo Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-wrapper { height: 200px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8f9fa; }
        .card-img-wrapper img { max-height: 100%; max-width: 100%; object-fit: contain; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Melbis Shop (Laravel Demo)</a>
    </div>
</nav>

<div class="container">
    <div class="row">
        <!-- Sidebar: Categories -->
        <div class="col-md-3 mb-4">
            <h4 class="mb-3">Categories</h4>
            <div class="list-group">
                <a href="{{ route('home') }}" 
                   class="list-group-item list-group-item-action {{ !$topicId ? 'active' : '' }}">
                    All goods
                </a>
                @foreach($topics as $cat)
                    <a href="{{ route('category', $cat->id) }}" 
                       class="list-group-item list-group-item-action {{ $topicId == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Main Content: Products -->
        <div class="col-md-9">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($goods as $item)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                                                    
                            <div class="card-img-wrapper border-bottom">
                                @if($item->images->count() > 0 && $item->images->first()->url != '')
                                    <img src="{{ $item->images->first()->url }}" alt="{{ $item->name }}">
                                @else
                                    <svg class="bd-placeholder-img card-img-top" width="100%" height="200" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false">
                                        <rect width="100%" height="100%" fill="#55595c"></rect>
                                        <text x="30%" y="50%" fill="#eceeef">No image available</text>
                                    </svg> 
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div class="text-muted small mb-2">Арт: {{ $item->code_shop }}</div>
                                <h5 class="card-title fs-6">{{ $item->name }}</h5>
                                
                                <div class="card-text text-muted small mb-3 flex-grow-1">
                                    {!! Str::limit(strip_tags($item->intro), 80) !!}
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="text-success fw-bold fs-5">
                                        {{ number_format($item->price, 2, '.', ' ') }} ₴
                                    </span>
                                    <button class="btn btn-sm btn-primary melbis_btn_add" data-id="{{ $item->id }}">Buy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5 mb-5">
                {{ $goods->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="melbis_win_basket" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Shopping cart</h5>        
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="melbis_btn_checkout">
            <i class="fas fa-shopping-cart"></i> Checkout
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" id="melbis_win_checkout" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Your order</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="melbis_form_order">                
            <h4 class="border-bottom pb-1">Cart</h4>
            <div id="melbis_order_goods"></div>            
        </form>        
      </div>                         
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="melbis_btn_finish">
            <i class="fas fa-paper-plane"></i> Finalize
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" id="melbis_win_finish" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Your order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4 class="text-center">Order successfully placed!</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $.ajaxSetup(
    {
        headers: 
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Add to basket
    $('.melbis_btn_add').on('click', function(event) 
    {
        event.preventDefault();
        
        let btn = $(this);
        let id = btn.data('id');
        let name = $(this).parents('.card').find('h5').text();        

        $.post('/cart/add', { id: id }, function(data) 
        {
            if ( data.result === 'OK' ) 
            {
                $('#melbis_win_basket .modal-body').html(
                    '<p><b>' + name + '</b></p><p>Product has been successfully added to your cart</p>'
                );
                $('#melbis_win_basket').modal('show');
            }
        }, 'json');
    });

    // Go to checkout
    $('#melbis_btn_checkout').on('click', function(event) 
    {
        $('#melbis_win_basket').modal('hide');
        $('#melbis_win_checkout').modal('show');      
    });

    // Load checkout 
    $('#melbis_win_checkout').on('show.bs.modal', function (event) 
    {     
        $('#melbis_order_goods').html('<div class="d-flex justify-content-center m-5"><div class="spinner-border text-primary" role="status"></div></div>');
        $.post('/cart/goods', {}, function(data) 
        { 
            $('#melbis_order_goods').html(data); 
        });
    });

    // Remove from basket
    $('#melbis_win_checkout').on('click', '.melbis_btn_remove', function(event) 
    {  
        let btn = $(this);    
        btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        $.post('/cart/remove', { id: btn.data('id') }, function(data) 
        {                                      
            $('#melbis_order_goods').html(data);                           
        });  
    });

    // Finalize order
    $('#melbis_btn_finish').on('click', function(event) 
    {
        event.preventDefault();
        
        let btn = $(this);
        let originalText = btn.html();
        
        // Show loading state
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $('#melbis_alert_error').addClass('d-none');

        // Send request to save order
        $.post('/cart/save', {}, function(data) {
            
            // Restore button state
            btn.prop('disabled', false).html(originalText);
            
            if (data.result !== 'OK') {
                // Show error message 
                $('#melbis_alert_error span').html('[' + data.result + '] ' + data.message);
                $('#melbis_alert_error').removeClass('d-none');
            } else {
                // Hide checkout window and show success window
                $('#melbis_win_checkout').modal('hide');
                $('#melbis_win_finish').modal('show');
            }
            
        }, 'json');
    });    


</script>



</body>
</html>