import { router } from "@inertiajs/react";
import DataTable from "react-data-table-component";

export default function ListingAllModule({ modules }) {
    const data = modules;

    const columns = [
        {
            name: "Module Name",
            selector: (row) => row.name,
            sortable: true,
        },
        {
            name: "Action",
            cell: (row) => (
                <>
                    <span
                        onClick={() => router.get(`/${row.name}`)}
                        className="mr-2 cursor-pointer"
                    >
                        View Module
                    </span>
                </>
            ),
        },
    ];

    return (
        <div className="background-gradient min-h-screen">
            <div className="container mx-auto p-10 flex justify-center">
                <div className="w-full max-w-full">
                    <div className="bg-white rounded-lg shadow-md p-8">
                        <DataTable columns={columns} data={data} />
                    </div>
                </div>
            </div>
        </div>
    );
}
