<!-- Featured Start -->
  @extends('client.layouts.app')
  @section('title', 'Cart')
  @section('content')

      <div class="row px-xl-5">
          @if (session('message'))
              <div class="row">
                  <h3 class="text-danger">{{ session('message') }}</h3>
              </div>
          @endif
          <div class="col-lg-8 table-responsive mb-5">
              <table class="table table-bordered text-center mb-0">
                  <thead class="bg-secondary text-dark">
                      <tr>
                          <th>Sản phẩm</th>
                          <th>Giá</th>
                          <th>Khối lượng</th>
                          <th>Giá giảm %</th>
                          <th>Số lượng</th>
                          <th>Tổng tiền</th>
                          <th>Thao tác</th>
                      </tr>
                  </thead>
                  <tbody class="align-middle">
                      @foreach ($cart->products as $item)
                          <tr id="row-{{ $item->id }}">
                              <td class="align-middle"><img src="{{ $item->product->image_path }}" alt=""
                                      style="width: 50px;">
                                  {{ $item->product->name }}</td>
                              <td class="align-middle">
                                  <p
                                      style="{{ $item->product->sale ? 'text-decoration: line-through' : '' }};                                                                                                                                                                                                                                                 ">
                                      {{ $item->product->price }} VNĐ
                                  </p>

                                  @if ($item->product->sale)
                                      <p
                                          style="
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ">
                                          {{ $item->product->sale_price }} VNĐ
                                      </p>
                                  @endif
                              </td>
                              <td class="align-middle">{{ $item->product_size }}</td>
                              <td class="align-middle">{{ $item->product->sale }}</td>
                              <td class="align-middle">
                                  <div class="input-group quantity mx-auto" style="width: 100px;">
                                      <div class="input-group-btn">
                                          <button class="btn btn-sm btn-primary btn-minus btn-update-quantity"
                                              data-action="{{ route('client.carts.update_product_quantity', $item->id) }}"
                                              data-id="{{ $item->id }}">
                                              <i class="fa fa-minus"></i>
                                          </button>
                                      </div>
                                      <input type="number" class="form-control form-control-sm bg-secondary text-center p-0"
                                          id="productQuantityInput-{{ $item->id }}" min="1"
                                          value="{{ $item->product_quantity }}">
                                      <div class="input-group-btn">
                                          <button class="btn btn-sm btn-primary btn-plus btn-update-quantity"
                                              data-action="{{ route('client.carts.update_product_quantity', $item->id) }}"
                                              data-id="{{ $item->id }}">
                                              <i class="fa fa-plus"></i>
                                          </button>
                                      </div>
                                  </div>
                              </td>
                              <td class="align-middle">
                                  <span
                                      id="cartProductPrice{{ $item->id }}">{{ $item->product->sale ? $item->product->sale_price * $item->product_quantity : $item->product->price * $item->product_quantity }} VNĐ</span>


                              </td>
                              <td class="align-middle">
                                  <button class="btn btn-sm btn-primary btn-remove-product"
                                      data-action="{{ route('client.carts.remove_product', $item->id) }}"><i
                                          class="fa fa-times"></i></button>
                              </td>

                          </tr>
                      @endforeach

                  </tbody>
              </table>
          </div>
          <div class="col-lg-4">
              <form class="mb-5" method="POST" action="{{ route('client.carts.apply_coupon') }}">
                  @csrf
                  <div class="input-group">
                      <input type="text" class="form-control p-4" value="{{ Session::get('coupon_code') }}"
                          name="coupon_code" placeholder="Mã Coupon">
                      <div class="input-group-append">
                          <button class="btn btn-primary">Áp dụng</button>
                      </div>
                  </div>
              </form>
              <div class="card border-secondary mb-5">
                  <div class="card-header bg-secondary border-0">
                      <h4 class="font-weight-semi-bold m-0">Thanh toán</h4>
                  </div>
                  <div class="card-body">
                      <div class="d-flex justify-content-between mb-3 pt-1">
                          <h6 class="font-weight-medium">Tổng sản phẩm</h6>
                          <h6 class="font-weight-medium total-price" data-price="{{ $cart->total_price }}">
                              {{ $cart->total_price }} VNĐ</h6>

                      </div>


                      @if (session('discount_amount_price'))
                          <div class="d-flex justify-content-between">
                              <h6 class="font-weight-medium">Giá Coupon </h6>
                              <h6 class="font-weight-medium coupon-div"
                                  data-price="{{ session('discount_amount_price') }}">
                                  {{ session('discount_amount_price') }} VNĐ</h6>
                          </div>
                      @endif

                  </div>
                  <div class="card-footer border-secondary bg-transparent">
                      <div class="d-flex justify-content-between mt-2">
                          <h5 class="font-weight-bold">Tổng cộng</h5>
                          <h5 class="font-weight-bold total-price-all"></h5>
                      </div>
                      <a href="{{ route('client.checkout.index') }}" class="btn btn-block btn-primary my-3 py-3">Tiếp tục</a>
                  </div>
              </div>
          </div>
      </div>

  @endsection
  @section('script')
      <script>
        $(function(){

            getTotalValue();
            function getTotalValue() {
    // console.log('called');
    let total = parseFloat($('.total-price').text().replace('$', '')) || 0;
    let couponPrice = parseFloat($('.coupon-div')?.data('price')) || 0;
    $('.total-price-all').text(`${total - couponPrice} VNĐ`);
}

            $(document).on("click", ".btn-remove-product", function (e) {
                let url = $(this).data("action");
                confirmDelete()
                    .then(function () {
                        $.post(url, (res) => {
                            let cart = res.cart;
                            let cartProductId = res.product_cart_id;
                            $("#productCountCart").text(cart.product_count);
                            $(".total-price")
                                .text(`$${cart.total_price}`)
                                .data("price", cart.product_count);
                            $(`#row-${cartProductId}`).remove();
                            getTotalValue();
                        });
                    })
                    .catch(function () {});
            });

            const TIME_TO_UPDATE = 500;
            $(document).on('click', '.btn-update-quantity', _.debounce(function(e){
                let url = $(this).data('action')
                let id = $(this).data('id')
                let data = {
                    product_quantity: $(`#productQuantityInput-${id}`).val()
                }

                $.post(url, data, res => {
                    let cartProductId = res.product_cart_id;
                    let cart = res.cart;
                    $('#productCountCart').text(cart.product_count)
                    if(res.remove_product){
                        $(`#row-${cartProductId}`).remove();
                    }
                    else{
                        $(`#cartProductPrice${cartProductId}`).html(`$${res.cart_product_price}`);
                    }
                    $('.total-price').text(`$${cart.total_price}`);
                    getTotalValue();
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Tăng số lượng thành công",
                        showConfirmButton: false,
                        timer: 1000,
                    });
                });
            }, TIME_TO_UPDATE))
        });
       
      </script>
      {{-- <script src="{{ asset('client/cart/cart.js') }}"></script> --}}
  @endsection

