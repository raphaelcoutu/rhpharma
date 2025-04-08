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
    columnHelper.accessor("firstname", {
        header: () => "Prénom",
        cell: (info) => info.getValue(),
    }),
    columnHelper.accessor("lastname", {
        cell: (info) => info.getValue(),
        header: () => "Nom",
    }),
    columnHelper.accessor("branch.name", {
        cell: (info) => info.getValue(),
        header: () => "Branche",
        enableGlobalFilter: false,
    }),
    columnHelper.accessor("workdays_per_week", {
        cell: (info) => info.getValue(),
        header: () => "# Jrs/sem",
    }),
];

export default function Index({ users }) {
    const [globalFilter, setGlobalFilter] = useState("");

    const table = useReactTable({
        data: users,
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
            <Head title="Utilisateurs" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h2 className="font-bold text-2xl">Liste d'utilisateurs</h2>
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
                                    href={route("users.create")}
                                >
                                    Ajouter un utilisateur
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
                                                        "users.edit",
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
