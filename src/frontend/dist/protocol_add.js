function formSubmit_EventListener(p_event)
{
   // alert("formSubmit_EventListener");

   p_event.preventDefault();
   sendProtoAddRequest_ajax();
}

function sendProtoAddRequest_ajax()
{
   xhr = new XMLHttpRequest();
   xhr.onreadystatechange = function() {
      if (this.readyState === 4) {
         if (this.status >= 200 && this.status < 300) {
            // console.log(this.responseText); return;
            let protoAdd_ResponseJson = JSON.parse(this.responseText);
            console.log(protoAdd_ResponseJson);
            alert(protoAdd_ResponseJson.message);
            if (protoAdd_ResponseJson.status === "success") {
               window.location.replace("http://localhost/protocol.php")
            }
         }
      }
   };

   xhr.open("POST","http://localhost/proto_add.php");
   // xhr.open("POST","http://localhost/ajax_test.php");   
   xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
   let requestBody = 
      JSON.stringify(
         Object.fromEntries(
            new FormData(document.getElementById("proto-add-form")))
      );
   console.log(requestBody);

   xhr.send(requestBody);

}

window.addEventListener("DOMContentLoaded", function () {
   let protoAddForm = document.getElementById("proto-add-form");
   protoAddForm.addEventListener("submit", formSubmit_EventListener);
});