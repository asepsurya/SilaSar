@extends('layout.main')
@section('title', 'Data IKM')
@section('container')
<div class="max-w-3xl mx-auto mt-10">
    <div class="bg-gray-900 text-white rounded-lg p-5 shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Update Aplikasi</h2>
        <p class="text-gray-400 mb-3">Klik tombol di bawah untuk memperbarui aplikasi dari GitHub.</p>

        <button id="updateBtn"
            class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white font-semibold mb-4">Jalankan Update</button>

        <div id="terminal"
            class="bg-black text-green-400 font-mono text-sm p-3 rounded overflow-y-auto h-80 border border-gray-700"></div>
    </div>
</div>

<script>
document.getElementById('updateBtn').addEventListener('click', function() {
    const btn = this;
    const terminal = document.getElementById('terminal');
    btn.disabled = true;
    terminal.innerHTML = "Menjalankan update...\n";

   fetch('{{ route('update.run') }}', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
})
.then(async res => {
    if (!res.ok) {
        const text = await res.text();
        throw new Error("Server Error: " + res.status + " — " + text.substring(0, 200));
    }
    return res.json();
})
.then(data => {
    terminal.innerHTML += "\n" + data.log;
})
.catch(err => {
    terminal.innerHTML += "\n❌ Error: " + err.message;
})
.finally(() => {
    btn.disabled = false;
});
});
</script>
@endsection
