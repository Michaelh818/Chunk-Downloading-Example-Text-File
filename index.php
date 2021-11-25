<script>
var xhr = new XMLHttpRequest();

// collection the alphabet in here in 4 chunks
let collection = [];

xhr.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 206) {
        // 206 partial data
        // console.log(xhr.responseText)
        collection.push(xhr.response); 
        //console.log(collection)
        
        
    }
};

// grabbign abcdef
xhr.open("GET", "api.php", true);
xhr.responseType = 'blob';
xhr.setRequestHeader("Range", "bytes=0-100000");
xhr.send();

setTimeout(function(){
    xhr.open("GET", "api.php", true);
    xhr.setRequestHeader("Range", "bytes=100001-200000");
    xhr.send();
}, 1000)

setTimeout(function(){
    xhr.open("GET", "api.php", true);
    xhr.setRequestHeader("Range", "bytes=200001-300000");
    xhr.send();
}, 2000)

setTimeout(function(){
    xhr.open("GET", "api.php", true);
    xhr.setRequestHeader("Range", "bytes=300001-"); // it gets the size of the file in the backend and completes the entire download this way.
    xhr.send();
}, 3000)

setTimeout(function(){
    // Create a new Blob object using the 
      //response data of the onload object
      var blob = new Blob(collection, { type: "image/png" });
      //Create a link element, hide it, direct 
      //it towards the blob, and then 'click' it programatically
      let a = document.createElement("a");
      a.style = "display: none";
      document.body.appendChild(a);
      //Create a DOMString representing the blob 
      //and point the link element towards it
      let url = window.URL.createObjectURL(blob);
      a.href = url;
      a.download = 'pic.png';
      //programatically click the link to trigger the download
      a.click();
      //release the reference to the file by revoking the Object URL
      window.URL.revokeObjectURL(url);
      a.remove();

//       var blob = new Blob([data], { type: "image/png" });
// var url = URL.createObjectURL(blob);
// var img = new Image();
// img.src = url;
// console.log("data length: " + data.length);
// console.log("url: " + url);
// document.body.appendChild(img);
}, 4000)
</script>
CHECK CONSOLE