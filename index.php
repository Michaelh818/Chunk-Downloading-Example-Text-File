<script>
var xhr = new XMLHttpRequest();
let blob;

// collection the alphabet in here in 4 chunks
let collection = [];
let totalLoaded = 0;
let totalLength =  54525952; // 50mb since our db is capped at this anyway...
let chunks = 50;
let chunkSize = totalLength / chunks;
var ms = 3000;
var start = 0;
var end = chunkSize;

var finished = false;
xhr.onload = function(e){
    console.log('loaded');
    start = end + 1;
    end = end + chunkSize;
    collection.push(xhr.response);
    console.log("percent: " + Math.round(start/end*100))
    if (totalLength > end)
        chunkData(start, end, totalLength);
    else {
        if (finished){
            // //Create a link element, hide it, direct 
            // //it towards the blob, and then 'click' it programatically
            // let a = document.createElement("a");
            // a.style = "display: none";
            // document.body.appendChild(a);
            // //Create a DOMString representing the blob 
            // //and point the link element towards it
            // let url = window.URL.createObjectURL(blob);
            // a.href = url;
            // a.download = 'test.mp4';
            // //programatically click the link to trigger the download
            // a.click();
            // //release the reference to the file by revoking the Object URL
            // window.URL.revokeObjectURL(url);
            // a.remove();
        } else {
            finished = true;
            chunkData(start, end, totalLength);
            xhr.open("GET", "download.php", true);
            xhr.responseType = 'blob';
            xhr.setRequestHeader("Range", "bytes=" + start + "-");
            xhr.send(null);
            blob = new Blob(collection, { type: "video/mp4" });
        }

        // xhr = null;
    }
}
xhr.onreadystatechange = function(e) {
    if (this.readyState == 4 && this.status == 200) {
        totalLength = xhr.getResponseHeader('Total-Content-Length');
        chunkSize = Math.round(totalLength / chunks);
        console.log(e)
    }
    else if (this.readyState == 4 && this.status == 206) {
        totalLength = xhr.getResponseHeader('Total-Content-Length');
        chunkSize = Math.round(totalLength / chunks);
        // collection.push(xhr.response);
    }
};
xhr.onprogress = function(e) {
    totalLength = xhr.getResponseHeader('Total-Content-Length');
    chunkSize = Math.round(totalLength/chunks);
    totalLoaded += e.loaded;
    // console.log("totalLoaded:" + totalLoaded);
    // console.log("totalLength:" + totalLength);
    // var percent = Math.round( (totalLoaded / totalLength)*100);
}

function getFileSize(_callback){
    console.log('getting file size')
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "file-size.php", true);
    xhr.send(null);
    console.log(totalLength)
    xhr.onreadystatechange = function(e) {
    if (this.readyState == 4 && this.status == 200) {
        totalLength = xhr.getResponseHeader('Total-Content-Length');
        chunkSize = totalLength / chunks;
        console.log(e);
        xhr = null;
        _callback();
    }
}
}

function chunkData(start, end, totalLength) {
    chunkSize = Math.round(totalLength/chunks);
    end = end > totalLength ? '' : end; // finish downloading remaining chunk
    xhr.open("GET", "download.php", true);
    xhr.responseType = 'blob';
    xhr.setRequestHeader("Range", "bytes=" + start + "-" + end);
    xhr.send();
}
function fire(){

chunkData(start, end, totalLength);

}
getFileSize( fire );




let process = setInterval(() => {
    if(finished) {
            // Create a new Blob object using the 
      //response data of the onload object
      var blob = new Blob(collection, { type: "video/mp4" });
      //Create a link element, hide it, direct 
      //it towards the blob, and then 'click' it programatically
      let a = document.createElement("a");
      a.style = "display: none";
      document.body.appendChild(a);
      //Create a DOMString representing the blob 
      //and point the link element towards it
      let url = window.URL.createObjectURL(blob);
      a.href = url;
      a.download = 'test.mp4';
      //programatically click the link to trigger the download
      a.click();
      //release the reference to the file by revoking the Object URL
      window.URL.revokeObjectURL(url);
      a.remove();
      setTimeout(function(){
        clearInterval(process);
      }, 500)
    }
}, 1000);

// setTimeout(function(){
//     // Create a new Blob object using the 
//       //response data of the onload object
//       var blob = new Blob(collection, { type: "video/mp4" });
//       //Create a link element, hide it, direct 
//       //it towards the blob, and then 'click' it programatically
//       let a = document.createElement("a");
//       a.style = "display: none";
//       document.body.appendChild(a);
//       //Create a DOMString representing the blob 
//       //and point the link element towards it
//       let url = window.URL.createObjectURL(blob);
//       a.href = url;
//       a.download = 'test.mp4';
//       //programatically click the link to trigger the download
//       a.click();
//       //release the reference to the file by revoking the Object URL
//       window.URL.revokeObjectURL(url);
//       a.remove();
// }, 250 * (chunks+1))
</script>
CHECK CONSOLE