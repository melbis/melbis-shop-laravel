<table class="table align-middle">
    <thead class="table-light">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Amount</th>
            <th scope="col">Price</th>
            <th scope="col">To do</th>            
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <th scope="row">{{ $item['store_id'] }}</th>
                <td>{{ $item['store_name'] }}</td>
                <td>{{ $item['amount'] }} шт.</td>
                <td>{{ number_format($item['out_price'], 2, '.', ' ') }} ₴</td>
                <td>                    
                    <button type="button" class="btn btn-warning melbis_btn_remove btn-sm" data-id="{{ $item['store_id'] }}">
                        <i class="fas fa-minus-circle"></i> Remove
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>