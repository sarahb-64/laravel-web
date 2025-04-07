<form action="/generate-content" method="POST">
    @csrf
    <label for="prompt">Enter your content idea:</label>
    <input type="text" name="prompt" id="prompt" required>
    <button type="submit">Generate Content</button>
</form>