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
        <div class="row">
            <div class="col" >
                <h2>Makes</h2>
                <p>List of all vehicle manufacturers.</p>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="make-data-table" class="table" style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Manufacture Id</th>
                    <th>Manufacture Name</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $makes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                   
                    <tr>
                        <td><?php echo e(++$key); ?></td>
                        <td><?php echo e($item->mfrId); ?></td>
                        <td><?php echo e($item->mfrName); ?></td>

                        
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

        </table>
        
        <div class="pull-right">
            <?php echo e($makes->links()); ?>

        </div>
    </div>

</section>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>

    $(document).ready(function() {
        var table = $('#make-data-table').DataTable( {
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

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ERP\resources\views/makes/get.blade.php ENDPATH**/ ?>