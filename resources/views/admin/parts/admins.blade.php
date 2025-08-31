<!-- admin/parts/admins.blade.php -->
<div x-show="activeTab === 'admins'">
    <h2>Administración de administradores</h2>
    <!-- ... contenido de administradores -->

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
            <tr>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
