{{-- @if($category_permission_active)
                <li id="category-menu"><a href="{{route('category.index')}}">{{__('file.category')}}</a></li>
                @endif
                @if($index_permission_active)
                <li id="product-list-menu"><a href="{{route('products.index')}}">{{__('file.product_list')}}</a></li>
                <?php
                $add_permission = DB::table('permissions')->where('name', 'products-add')->first();
                $add_permission_active = DB::table('role_has_permissions')->where([
                    ['permission_id', $add_permission->id],
                    ['role_id', $role->id]
                ])->first();
                ?>
                @if($add_permission_active)
                <li id="product-create-menu"><a href="{{route('products.create')}}">{{__('file.add_product')}}</a></li>
                @endif
                @endif
                @if($print_barcode_active)
                <li id="printBarcode-menu"><a href="{{route('product.printBarcode')}}">{{__('file.print_barcode')}}</a></li>
                @endif
                @if($adjustment_active)
                <li id="adjustment-list-menu"><a href="{{route('qty_adjustment.index')}}">{{trans('file.Adjustment List')}}</a></li>
                <li id="adjustment-create-menu"><a href="{{route('qty_adjustment.create')}}">{{trans('file.Add Adjustment')}}</a></li>
                @endif
                @if($stock_count_active)
                <li id="stock-count-menu"><a href="{{route('stock-count.index')}}">{{trans('file.Stock Count')}}</a></li>
                @endif --}}