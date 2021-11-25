<script>
var xhr = new XMLHttpRequest();
let collection = null;

xhr.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 206) {
        // 206 partial data
        console.log(xhr.responseText)
        collection += xhr.responseText;
        
        
    }
};

// grabbign abcdef
xhr.open("GET", "api.php", true);
xhr.setRequestHeader("Range", "bytes=0-5");
xhr.send();

setTimeout(function(){
    xhr.open("GET", "api.php", true);
    xhr.setRequestHeader("Range", "bytes=6-10");
    xhr.send();
}, 1000)

setTimeout(function(){
    xhr.open("GET", "api.php", true);
    xhr.setRequestHeader("Range", "bytes=11-16");
    xhr.send();
}, 2000)

setTimeout(function(){
    xhr.open("GET", "api.php", true);
    xhr.setRequestHeader("Range", "bytes=17-21");
    xhr.send();
}, 3000)

// setTimeout(function(){
//     console.log(collection);
// }, 4000)
</script>
CHECK CONSOLE