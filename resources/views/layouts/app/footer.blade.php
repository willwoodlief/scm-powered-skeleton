<div class="footer out-footer">
    <div class="copyright">
        <p>
            Copyright © Salvage Contractors Midwest, Inc 2023
        </p>

        @filter(\App\Plugins\Plugin::FILTER_FRAME_EXTRA_FOOTER_ELEMENTS,'')

    </div>
</div>

<div class="offcanvas offcanvas-end customeoff" tabindex="-1" id="offcanvasExample1">
    <div class="offcanvas-header">
        <h5 class="modal-title" id="#gridSystemModal1">Add New To-Do</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="container-fluid">
            @include('tasks.new_todo')
        </div>
    </div>
</div>








