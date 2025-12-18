<div class="block">
    <h2 class="block-title-medium">Shipment Detail</h2>
    <div class="list no-hairlines-md">
        <form id="step-4">
            <ul>
                <li class="item-content item-input item-input-outline">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Transaction Type *</div>
                        <div class="item-input-wrap">
                            <select name="payment_type">
                                <option value="CI">Prepaid</option>
                                <option value="CD">Collect</option>
                            </select>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search destination">
                        <select name="destinationbranch_id">
                            <option selected disabled style="display:none;" value="">--Select destination--</option>
                            @foreach($branches as $row)    
                                <option value="{{$row->branchoffice_no}}">{{$row->branchoffice_description}}</option>
                            @endforeach
                        </select>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title">Destination *</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="item-content item-input item-input-outline item-input-focused">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Shipment Type *</div>
                        <div class="item-input-wrap">
                            <select name="shipment_type">
                                <option value="BREAKABLE" selected>Breakable</option>
                                <option value="PERISHABLE">Perishable</option>
                                <option value="LETTER">Letter</option>
                                <option value="OTHERS">Others</option>
                            </select>
                        </div>
                    </div>
                </li>

                <li class="item-content item-input item-input-outline item-input-focused declared-value">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Declared Value *</div>
                        <div class="item-input-wrap">
                            <input type="number" name="declared_value" readonly value="1000" >
                            <span class="input-clear-button"></span>
                        </div>
                    </div>
                </li>

                <li class="item-content item-input item-input-outline">
                
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Discount Coupon</div>
                        <div class="item-input-wrap">
                        <input type="text" name="discount_coupon" class="discount-coupon">
                        <span class="input-clear-button"></span>
                        </div>
                    </div>

                    <div class="item-media">
                        <a class="button" id="verify">
                            <i class="icon f7-icons">search</i>
                        </a>
                    </div>
                </li>

                <li>
                
                    <div class="card data-table data-table-collapsible data-table-init">
                        <div class="card-header">
                            <div class="data-table-title">SHIPMENTS</div>
                            <div class="data-table-actions"><a class="button" id="add-item">Add</a></div>
                        </div>
                        <div class="card-content">
                            <div class="list shipments-list">
                                <ul>

                                </ul>
                            </div>
                        </div>
                    </div>

                </li>
            </ul>
        </form>
    </div>
</div>