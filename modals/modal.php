<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content" id='myModalContent'>
    <div class="modal-header">
        <h5 class="modal-title" id="myModalLabelh5">Message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body" id="myModalOutput">
        
    </div>
    <div class="modal-footer" id="myModalButtons">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
    </div>
</div>
</div>

<script>
    var myModal = new bootstrap.Modal(document.getElementById('myModal'))
</script>