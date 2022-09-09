 <?php $__env->startSection('content'); ?>
<?php if(session()->has('message')): ?>
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo session()->get('message'); ?></div>
<?php endif; ?>
<?php if(session()->has('not_permitted')): ?>
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div>
<?php endif; ?>

<section>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="text-left"><?php echo e(trans('file.Sales Invoices List')); ?></h3>
            </div>
            </div>
            <?php echo Form::close(); ?>

        </div>
            <!-- <a href="<?php echo e(route('sales.create')); ?>" class="btn btn-info ml-4"><i class="dripicons-plus"></i> <?php echo e(trans('file.Add')); ?></a>&nbsp; -->
            <!-- <a href="<?php echo e(url('sales/sale_by_csv')); ?>" class="btn btn-primary"><i class="dripicons-copy"></i> <?php echo e(trans('file.Import Sale')); ?></a> -->
    </div>
    <div class="card p-3">
    <div class="table-responsive">
        <table id="sale-table" class="table sale-list" style="width: 100%">
            <thead>
                <tr>
                    <th><?php echo e(trans('file.Date')); ?></th>
                    <th><?php echo e(trans('file.Invoice Number')); ?></th>
                    <!-- <th><?php echo e(trans('file.Biller')); ?></th> -->
                    <th><?php echo e(trans('file.Customer')); ?></th>
                    <!-- <th><?php echo e(trans('file.Sale Status')); ?></th> -->
                    <!-- <th><?php echo e(trans('file.Payment Status')); ?></th> -->
                    <th><?php echo e(trans('file.Grand Total')); ?></th>
                    <!-- <th><?php echo e(trans('file.Payment Method')); ?></th> -->

                    <!-- <th><?php echo e(trans('file.Paid')); ?></th> -->
                    <th><?php echo e(trans('file.Status')); ?></th>
                    <th><?php echo e(trans('file.action')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php 
                $customer=App\Customer::find($invoice->customer_id); ?>
                <tr>
                    <td><?php echo e($invoice->created_at->format('d-m-Y')); ?></td>
                    <td><?php echo e($invoice->invoice_number); ?></td>
                    <td><?php echo e($customer->name); ?></td>
                    <td><?php echo e($invoice->grand_total); ?></td>
                    <?php if($invoice->status == 1): ?>
                    <td><div class="btn-group">
                            <button type="button" class="btn btn-sm dropdown-toggle btn-danger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo e(trans('file.Un Paid')); ?>

                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                <a class="btn btn-link" href="<?php echo e(route('sales.changeInvoiceStatus',[$invoice->id,3])); ?>"><?php echo e(trans('file.Partial')); ?></a>
                                </li>
                                <li>
                                    <a class="btn btn-link" href="<?php echo e(route('sales.changeInvoiceStatus',[$invoice->id,2])); ?>"> <?php echo e(trans('file.Paid')); ?></a>
                                </li>
                            </ul>
                        </div></td>
                    <?php elseif($invoice->status == 2): ?>
                    <td><div class="btn-group">
                            <button type="button" class="btn btn-sm dropdown-toggle btn-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo e(trans('file.Paid')); ?>

                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <!-- <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                <a class="btn btn-link" href="<?php echo e(route('sales.cancelEstimate',$invoice->id)); ?>"><?php echo e(trans('file.Partial')); ?></a>
                                </li>
                                <li>
                                    <a class="btn btn-link" href="<?php echo e(route('sales.acceptEstimate',$invoice->id)); ?>"> <?php echo e(trans('file.Paid')); ?></a>
                                </li> -->
                            </ul>
                        </div></td>
                    <?php else: ?>
                    <td><div class="btn-group">
                            <button type="button" class="btn btn-sm dropdown-toggle btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo e(trans('file.Partial')); ?>

                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <!-- <li>
                                <a class="btn btn-link" href="<?php echo e(route('sales.cancelEstimate',$invoice->id)); ?>"><?php echo e(trans('file.Partial')); ?></a>
                                </li> -->
                                <li>
                                    <a class="btn btn-link" href="<?php echo e(route('sales.changeInvoiceStatus',[$invoice->id,2])); ?>"> <?php echo e(trans('file.Paid')); ?></a>
                                </li>
                            </ul>
                        </div></td>
                    <?php endif; ?>
                    <td>
                    <?php if($invoice->status != 2): ?>
                        <a class="btn" href="<?php echo e(route('sales.editInvoice',$invoice->id)); ?>"><i class="dripicons-document-edit"></i></a>
                        <?php endif; ?>
                        <!-- <a class="btn" href="">$</a> -->
                        <!-- <a class="btn" href=""><i class="fa fa-file-pdf-o"></i></a> -->
                        <!-- <a class="btn" href="<?php echo e(route('sales.approveEstimate',$invoice->id)); ?>"><i class="fa fa-eye"></i></a> -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-refresh"></i>
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <?php if($invoice->status == 2): ?>
                                <li>
                                <a class="btn btn-link" href="<?php echo e(route('sales.reactivateEstimate',$invoice->id)); ?>"><?php echo e(trans('file.Delivery Slip')); ?></a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    </div>
</section>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ERP\resources\views/sale/invoices_index.blade.php ENDPATH**/ ?>