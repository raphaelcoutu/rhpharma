import SecondaryButton from '@/components/secondary-button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link } from '@inertiajs/react';
import { createColumnHelper, flexRender, getCoreRowModel, getFilteredRowModel, useReactTable } from '@tanstack/react-table';
import { Layers3, Plus } from 'lucide-react';
import { useState } from 'react';

const columnHelper = createColumnHelper();

const columns = [
    columnHelper.accessor('name', {
        header: () => 'Nom',
        cell: (info) => info.renderValue(),
    }),
    columnHelper.accessor('description', {
        header: () => 'Description',
        cell: (info) => info.getValue(),
    }),
    columnHelper.accessor('department_type.name', {
        header: () => 'Type',
        cell: (info) => info.getValue(),
    }),
    columnHelper.accessor('workplace.name', {
        header: () => 'Lieu de travail',
        cell: (info) => info.getValue(),
    }),
];

export default function Index({ departments }) {
    const [globalFilter, setGlobalFilter] = useState('');

    const table = useReactTable({
        data: departments,
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
            <Head title="Secteurs" />
            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div className="flex items-center gap-3 text-indigo-600">
                                <Layers3 className="h-5 w-5" />
                                <span className="text-sm font-semibold uppercase tracking-wider">Organisation</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Secteurs</h1>
                            <p className="mt-2 max-w-2xl text-sm text-gray-500">
                                Gérez les secteurs, leur type et leur lieu de travail.
                            </p>
                        </div>
                        <SecondaryButton as={Link} href={route('departments.create')} className="gap-2">
                            <Plus className="h-4 w-4" />
                            Ajouter un secteur
                        </SecondaryButton>
                    </div>

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
                                                <Link href={route('departments.edit', row.original.id)} className="hover:underline">
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
