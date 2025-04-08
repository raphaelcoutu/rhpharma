import { Head, Link } from "@inertiajs/react";
import AuthenticatedLayout from "@/layouts/authenticated-layout.jsx";
import {
    createColumnHelper,
    flexRender,
    getCoreRowModel,
    getFilteredRowModel,
    useReactTable,
} from "@tanstack/react-table";
import { useState } from "react";
import SecondaryButton from "@/components/secondary-button.jsx";
import { Input } from "@/components/ui/input.jsx";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table.jsx";

const columnHelper = createColumnHelper();

const columns = [
    columnHelper.accessor("name", {
        header: () => "Nom",
        cell: (info) => info.renderValue(),
    }),
    columnHelper.accessor("description", {
        header: () => "Description",
        cell: (info) => info.getValue(),
    }),
    columnHelper.accessor("department_type.name", {
        header: () => "Type",
        cell: (info) => info.getValue(),
    }),
    columnHelper.accessor("workplace.name", {
        header: () => "Lieu de travail",
        cell: (info) => info.getValue(),
    }),
];

export default function Index({ departments }) {
    const [globalFilter, setGlobalFilter] = useState("");

    const table = useReactTable({
        data: departments,
        columns,
        state: {
            globalFilter,
        },
        onGlobalFilterChange: setGlobalFilter,
        globalFilterFn: (row, columnId, filterValue) => {
            const normalize = (str) =>
                str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
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
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h2 className="font-bold text-2xl">Liste des secteurs</h2>
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="flex justify-between">
                                <Input
                                    className="w-1/4"
                                    type="search"
                                    value={globalFilter ?? ""}
                                    onChange={(e) =>
                                        setGlobalFilter(e.target.value)
                                    }
                                    placeholder="Rechercher"
                                />
                                <SecondaryButton
                                    as={Link}
                                    href={route("departments.create")}
                                >
                                    Ajouter un secteur
                                </SecondaryButton>
                            </div>

                            <Table className="w-full mt-2">
                                <TableHeader>
                                    {table
                                        .getHeaderGroups()
                                        .map((headerGroup) => (
                                            <TableRow key={headerGroup.id}>
                                                {headerGroup.headers.map(
                                                    (header) => (
                                                        <TableHead
                                                            key={header.id}
                                                        >
                                                            {header.isPlaceholder
                                                                ? null
                                                                : flexRender(
                                                                      header
                                                                          .column
                                                                          .columnDef
                                                                          .header,
                                                                      header.getContext(),
                                                                  )}
                                                        </TableHead>
                                                    ),
                                                )}
                                                <TableHead>Actions</TableHead>
                                            </TableRow>
                                        ))}
                                </TableHeader>
                                <TableBody>
                                    {table.getRowModel().rows.map((row) => (
                                        <TableRow key={row.id}>
                                            {row
                                                .getVisibleCells()
                                                .map((cell) => (
                                                    <TableCell key={cell.id}>
                                                        {flexRender(
                                                            cell.column
                                                                .columnDef.cell,
                                                            cell.getContext(),
                                                        )}
                                                    </TableCell>
                                                ))}
                                            <TableCell>
                                                <Link
                                                    href={route(
                                                        "departments.edit",
                                                        row.original.id,
                                                    )}
                                                    className="hover:underline"
                                                >
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
