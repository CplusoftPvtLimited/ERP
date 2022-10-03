 <?php $__env->startSection('content'); ?>
<?php if(session()->has('create_message')): ?>
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('create_message')); ?></div>
<?php endif; ?>
<?php if(session()->has('edit_message')): ?>
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('edit_message')); ?></div>
<?php endif; ?>
<?php if(session()->has('import_message')): ?>
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('import_message')); ?></div>
<?php endif; ?>
<?php if(session()->has('not_permitted')): ?>
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div>
<?php endif; ?>
<?php if(session()->has('message')): ?>
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('message')); ?></div>
<?php endif; ?>

<section>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class=""><?php echo e(trans('file.Products')); ?></h3>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="product-data-table" class="table" style="width: 100%">
            <thead>
                <tr>
                    <th>Article Number</th>
                    <th>Geeneric Article Description</th>
                    <th>Information Type Description</th>
                    <th>Text</th>
                    <th>Brand</th>
                    <th>Assembly Group Name</th>
                    
                    <th>Immediate Display</th>
                    <th>Manufacturer</th>
                    <th>Manufacturer Id</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                   
                    <tr>
                        <td><?php echo e($item->article->articleNumber); ?></td>
                        <td><?php echo e($item->article->genericArticleDescription); ?></td>
                        <td><?php echo e($item->articleText->informationTypeDescription); ?></td>
                        <td><?php echo e($item->articleText->text); ?></td>
                        <td><?php echo e(isset($item->article->brand->brandName) ? $item->article->brand->brandName : ""); ?></td>
                        <td><?php echo e($item->assemblyGroupNodes->assemblyGroupName); ?></td>
                        
                        <td><?php echo e($item->articleText->isImmediateDisplay); ?></td>
                        
                        <td><?php echo e($item->linkageTarget->mfrName); ?></td>
                        <td><?php echo e($item->linkageTarget->mfrId); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

        </table>
        
        <div class="pull-right">
            <?php echo e($products->links()); ?>

        </div>
    </div>

</section>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>

    $(document).ready(function() {
        var table = $('#product-data-table').DataTable( {
            responsive: true,
            fixedHeader: {
                header: true,
                footer: true
            },
            "processing": true,
            "paging" : false
            // "serverSide": true,
        } );

    } );
    $('select').selectpicker();

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ERP\resources\views/product/get.blade.php ENDPATH**/ ?>