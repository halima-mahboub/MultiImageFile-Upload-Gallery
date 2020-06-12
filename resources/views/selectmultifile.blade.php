<html xmlns="http://www.w3.org/1999/xhtml">
<body>
<h1>The input multiple attribute</h1>

<p>Try selecting more than one file when browsing for files.</p>

<form action="{{route('storeFiles')}}" enctype='multipart/form-data' method='post'>
@method('post')
@csrf
  <label for="files">Select files:</label>
  <input type="file" id="files" name="file[]" multiple><br><br>
  <input type="submit">
</form>

</body>
</html>