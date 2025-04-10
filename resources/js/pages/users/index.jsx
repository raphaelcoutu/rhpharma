import SecondaryButton from '@/components/secondary-button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link } from '@inertiajs/react';
import { createColumnHelper, flexRender, getCoreRowModel, getFilteredRowModel, useReactTable } from '@tanstack/react-table';
import { useState } from 'react';

const columnHelper = createColumnHelper();

const columns = [
    columnHelper.accessor('firstname', {
        header: () => 'Prénom',
        cell: (info) => info.getValue(),
    }),
    columnHelper.accessor('lastname', {
        cell: (info) => info.getValue(),
        header: () => 'Nom',
    }),
    columnHelper.accessor('branch.name', {
        cell: (info) => info.getValue(),
        header: () => 'Branche',
        enableGlobalFilter: false,
    }),
    columnHelper.accessor('workdays_per_week', {
        cell: (info) => info.getValue(),
        header: () => '# Jrs/sem',
    }),
];

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
            const normalize = (str) => str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            return normalize(String(row.getValue(columnId)))
                .toLowerCase()
                .includes(normalize(filterValue).toLowerCase());
        },
        getCoreRowModel: getCoreRowModel(),
        getFilteredRowModel: getFilteredRowModel(),
    });

    return (
        <AuthenticatedLayout>
            <Head title="Utilisateurs" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <h2 className="text-2xl font-bold">Liste d'utilisateurs</h2>
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="border-b border-gray-200 bg-white p-6">
                            <div className="flex justify-between">
                                <Input
                                    className="w-1/4"
                                    type="search"
                                    value={globalFilter ?? ''}
                                    onChange={(e) => setGlobalFilter(e.target.value)}
                                    placeholder="Rechercher"
                                />
                                <SecondaryButton as={Link} href={route('users.create')}>
                                    Ajouter un utilisateur
                                </SecondaryButton>
                            </div>

                            <Table className="mt-2 w-full">
                                <TableHeader>
                                    {table.getHeaderGroups().map((headerGroup) => (
                                        <TableRow key={headerGroup.id}>
                                            {headerGroup.headers.map((header) => (
                                                <TableHead key={header.id}>
                                                    {header.isPlaceholder ? null : flexRender(header.column.columnDef.header, header.getContext())}
                                                </TableHead>
                                            ))}
                                            <TableHead>Actions</TableHead>
                                        </TableRow>
                                    ))}
                                </TableHeader>
                                <TableBody>
                                    {table.getRowModel().rows.map((row) => (
                                        <TableRow key={row.id}>
                                            {row.getVisibleCells().map((cell) => (
                                                <TableCell key={cell.id}>{flexRender(cell.column.columnDef.cell, cell.getContext())}</TableCell>
                                            ))}
                                            <TableCell>
                                                <Link href={route('users.edit', row.original.id)} className="hover:underline">
                                                    Éditer
                                                </Link>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
