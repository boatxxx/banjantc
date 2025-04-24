<!-- resources/views/import.blade.php -->
<form action="{{ route('import.exce') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".xlsx, .xls">
    <button type="submit">Upload</button>
</form>
