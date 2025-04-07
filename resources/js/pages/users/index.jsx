import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/layouts/authenticated-layout.jsx';
import {
    createColumnHelper,
    flexRender,
    getCoreRowModel,
    getFilteredRowModel,
    useReactTable
} from '@tanstack/react-table';
import { useState } from 'react';
import TextInput from '@/components/text-input.jsx';
import SecondaryButton from '@/components/secondary-button.jsx';

const columnHelper = createColumnHelper()

const columns = [
    columnHelper.accessor('id', {
        header: () => 'ID',
        cell: info => info.renderValue(),
        enableGlobalFilter: false
    }),
    columnHelper.accessor('firstname', {
        header: () => 'Prénom',
        cell: info => info.getValue(),
    }),
    columnHelper.accessor('lastname', {
        cell: info => info.getValue(),
        header: () => 'Nom',
    }),
    columnHelper.accessor('branch.name', {
        cell: info => info.getValue(),
        header: () => 'Branche',
        enableGlobalFilter: false
    }),
    columnHelper.accessor('workdays_per_week', {
        cell: info => info.getValue(),
        header: () => '# Jrs/sem',
    }),
]

export default function Index({ users }) {
    const [globalFilter, setGlobalFilter] = useState('');

    const table = useReactTable({
        data: users,
        columns,
        state: {
            globalFilter,
        },
        onGlobalFilterChange: setGlobalFilter,
        globalFilterFn: (row, columnId, filterValue) => {
            const normalize = str => str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            return normalize(String(row.getValue(columnId)))
                .toLowerCase()
                .includes(normalize(filterValue).toLowerCase());
        },
        getCoreRowModel: getCoreRowModel(),
        getFilteredRowModel: getFilteredRowModel(),
    })

    return (
        <AuthenticatedLayout>
            <Head title="Utilisateurs"/>
            <div className="py-12">

                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h2 className="font-bold text-2xl">Liste d'utilisateurs</h2>
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className='flex justify-between'>
                                <TextInput className='w-1/4'
                                    value={globalFilter ?? ''}
                                           onChange={e => setGlobalFilter(e.target.value)}
                                       placeholder="Rechercher"/>
                                <SecondaryButton as={Link} href={route('users.create')}>Ajouter un utilisateur</SecondaryButton>
                            </div>

                            <table className='table w-full border-collapse border mt-2'>
                                <thead>
                                <tr className='border bg-slate-100 uppercase'>
                                    <th className='p-2 border'>Id</th>
                                    <th className='p-2 border'>Prénom</th>
                                    <th className='p-2 border'>Nom</th>
                                    <th className='p-2 border'>Branche</th>
                                    <th className='p-2 border'># Jrs/sem</th>
                                    <th className='p-2 border'>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                {table.getRowModel().rows.map(row => (
                                    <tr key={row.id} className='border'>
                                        {row.getVisibleCells().map(cell => (
                                            <td key={cell.id} className='p-2 border'>
                                                {flexRender(cell.column.columnDef.cell, cell.getContext())}
                                            </td>
                                        ))}
                                        <td className='p-2 border'><Link href={route('users.edit', row.original.id)}>Éditer</Link></td>
                                    </tr>
                                ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
