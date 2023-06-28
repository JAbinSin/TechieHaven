<!-- Modal -->
<div class="modal fade" id="myModalForm" tabindex="-1" aria-labelledby="myModalFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="modal-content" id='myModalFormContent'>
            <div class="modal-header">
                <h5 class="modal-title" id="myModalFormLabelh5">Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="myModalFormOutput">
                
            </div>
            <div class="modal-footer" id="myModalFormButtons">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    var myModalForm = new bootstrap.Modal(document.getElementById('myModalForm'))
</script>