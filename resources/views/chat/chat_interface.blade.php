<div id="chat">
    <div class="card-body msg_card_body dz-scroll" id="DZ_W_Contacts_Body3">
        <div id="chat-messages"></div>
    </div>
    <div class="card-footer type_msg">
        <form id="chat-form">
            @csrf
            <div class="input-group">
                <input type="text" id="chat-message" class="form-control" name="message"
                       placeholder="Type your message..." autocomplete="off" required title="message">

                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-location-arrow"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

