<div>
    {{-- Because she competes with no one, no one can compete with her. --}}

    <input
        type="text"
        wire:model.live="search"
        placeholder="Cari arsip..."
        class="border p-2 rounded w-full mb-3"
    >

    <table class="w-full border">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($arsips as $arsip)
                <tr>
                    <td>{{ $arsip->judul }}</td>
                    <td>{{ $arsip->tanggal }}</td>
                    <td>
                        @can('update', $arsip)
                            <button>Edit</button>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $arsips->links() }}


</div>
