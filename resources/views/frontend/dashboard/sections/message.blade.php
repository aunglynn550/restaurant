<div class="tab-pane fade" id="v-pills-message" role="tabpanel"
    aria-labelledby="v-pills-message-tab">
    <div class="fp_dashboard_body fp__change_password">
        <div class="fp__message">
            <h3>Message</h3>
            <div class="fp__chat_area">
                <div class="fp__chat_body">
                    <div class="fp__chating">
                        <!-- <div class="fp__chating_img">
                            <img src="images/service_provider.png" alt="person"
                                class="img-fluid w-100">
                        </div>
                        <div class="fp__chating_text">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                Pariatur qui amet aperiam, magni accusamus voluptatum
                                neque
                                aut tenetur odit officia fugit et sint harum inventore
                                recusandae id quibusdam ducimus consequuntur.</p>
                            <span>15 Jun, 2023, 05:26 AM</span>
                        </div>
                    </div>
                    <div class="fp__chating tf_chat_right">
                        <div class="fp__chating_img">
                            <img src="images/client_img_1.jpg" alt="person"
                                class="img-fluid w-100">
                        </div>
                        <div class="fp__chating_text">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            </p>
                            <span>15 Jun, 2023, 05:26 AM</span>
                        </div>
                    </div>
                    <div class="fp__chating">
                        <div class="fp__chating_img">
                            <img src="images/service_provider.png" alt="person"
                                class="img-fluid w-100">
                        </div>
                        <div class="fp__chating_text">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                Pariatur qui amet aperiam.</p>
                            <span>15 Jun, 2023, 05:26 AM</span>
                        </div>
                    </div>
                    <div class="fp__chating tf_chat_right">
                        <div class="fp__chating_img">
                            <img src="images/client_img_1.jpg" alt="person"
                                class="img-fluid w-100">
                        </div>
                        <div class="fp__chating_text">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                Pariatur qui amet aperiam, magni accusamus the in
                                voluptatum
                                neque
                                aut tenetur odit officia fugit et sint harum inventore
                                recusandae id quibusdam.</p>
                            <span>15 Jun, 2023, 05:26 AM</span>
                        </div>
                    </div>
                    <div class="fp__chating">
                        <div class="fp__chating_img">
                            <img src="images/service_provider.png" alt="person"
                                class="img-fluid w-100">
                        </div>
                        <div class="fp__chating_text">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            </p>
                            <span>15 Jun, 2023, 05:26 AM</span>
                        </div>
                    </div>
                    <div class="fp__chating tf_chat_right">
                        <div class="fp__chating_img">
                            <img src="images/client_img_1.jpg" alt="person"
                                class="img-fluid w-100">
                        </div>
                        <div class="fp__chating_text">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            </p>
                            <span>15 Jun, 2023, 05:26 AM</span>
                        </div> -->
                    </div>
                </div>
                <form class="fp__single_chat_bottom chat_input">
                    @csrf                  
                    <input type="hidden" name="msg_temp_id" class="msg_temp_id" value="">
                    <input type="text" placeholder="Type a message..." name="message" class="fp_send_message">
                    <input type="hidden" name="receiver_id" value="1">
                    <button class="fp__massage_btn" type="submit"><i class="fas fa-paper-plane"
                            aria-hidden="true"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function(){
        var userId = "{{ auth()->user()->id }}";

        function scrollToBottom(){
            let chatContent = $('.fp__chat_body');
             chatContent.scrollTop(chatContent.prop("scrollHeight"));
        }

            // Fetch conversations
            $('.fp_chat_message').on('click', function(){
                let senderId =1;               

                $.ajax({
                    method: 'GET',
                    url: '{{ route("chat.get-conversation", ":senderId") }}'.replace(":senderId",senderId),
                    beforeSend: function(){

                    },
                    success:function(response){
                        $('.fp__chat_body').empty()
                        $.each(response, function(index, message){
                            let avatar = "{{ asset(':avatar') }}".replace(':avatar', message.sender.avatar);
                            let html = `<div class="fp__chating ${message.sender_id == userId ? 'tf_chat_right' : ''}">
                                            <div class="fp__chating_img">
                                                <img src="${avatar}" alt="person" style="border-radius: 50%;"
                                                    class="img-fluid w-100">
                                            </div>
                                            <div class="fp__chating_text">
                                                <p>${message.message}</p>
                                                
                                            </div>
                                    </div>`
                            $('.fp__chat_body').append(html)
                            $('.unseen-message-count').text(0)
                        })
                        scrollToBottom()
                    },
                    error:function(xhr,status,error){

                    }
                })
            })//end function

            // Send Message
            $('.chat_input').on('submit', function(e){
                e.preventDefault();
                var msgId = Math.floor(Math.random() * (1 - 1000 + 1)) + 1000
                $('.msg_temp_id').val(msgId)
                let formData = $(this).serialize();
               $.ajax({
                method: 'POST',
                url: "{{ route('chat.send-message') }}",
                data: formData,
                beforeSend:function(){
                let message = $('.fp_send_message').val()
                let html =  `<div class="fp__chating tf_chat_right">
                            <div class="fp__chating_img">
                                <img src="{{ asset(auth()->user()->avatar) }}" alt="person"
                                    class="img-fluid w-100" style="border-radius: 50%;">
                            </div>
                            <div class="fp__chating_text">
                                <p>${message}</p>
                                <span class="msg_sending ${msgId}">Sending..</span>
                            </div>
                        </div>
                    </div>`
                $('.fp__chat_body').append(html)
                $('.fp_send_message').val("")
                scrollToBottom()
                },
                success: function(response){
                    if($('.msg_temp_id').val() == response.msgId){
                        console.log($('.'+msgId))
                        $('.'+msgId).remove()
                    }
                },
                error: function(xhr, status ,error){                    
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key,value){
                        toastr.error(value);
                    })
                }
               })
            })
    })
</script>

@endpush