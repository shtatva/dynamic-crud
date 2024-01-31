import DataTable from "react-data-table-component";
import { router } from "@inertiajs/react";
import Swal from "sweetalert2";

export default function Listing({ tables, tableName, tableFields }) {
    const columns = [];

    tableFields.forEach((element) => {
        columns.push({
            name: element.name,
            selector: (row) => row[element.name],
            sortable: true,
        });
    });

    const action = {
        name: "Action",
        cell: (row) => (
            <>
                <span
                    onClick={() => router.get(`/${tableName}/${row.id}/edit`)}
                    className="mr-2 cursor-pointer"
                >
                    Edit
                </span>
                <span
                    onClick={() => deleteData(row.id)}
                    className="cursor-pointer"
                >
                    Delete
                </span>
            </>
        ),
    };

    const deleteData = (id) => {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                router.visit(`/${tableName}/${id}`, {
                    method: "delete",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    onSuccess: (page) => {
                        // console.log(page);
                    },
                });

                Swal.fire({
                    title: "Deleted!",
                    text: "Your data has been deleted.",
                    icon: "success",
                });
            }
        });
    };

    columns.push(action);

    const data = tables;

    return (
        <div className="background-gradient min-h-screen">
            <div className="container mx-auto p-10 flex justify-center">
                <div className="w-full max-w-full">
                    <div className="bg-white rounded-lg shadow-md p-8">
                        <div className="flex items-center justify-end">
                            <button
                                type="button"
                                className="bg-blue-500 text-white px-4 py-2 rounded-md"
                                onClick={() =>
                                    router.get(`/${tableName}/create`)
                                }
                            >
                                Create {tableName}
                            </button>
                        </div>

                        <DataTable columns={columns} data={data} />
                    </div>
                </div>
            </div>
        </div>
    );
}
