import { useEffect, useState } from "react";
import { router } from "@inertiajs/react";

export default function CreateMigration({
    formOption,
    isEdit = false,
    initialFormData = {},
    tableId = null,
}) {
    const formObject = {
        name: "",
        type: "string",
        length_value: "255",
        default: "None",
        default_value: "",
        attributes: "",
        isNull: false,
        index: "",
    };

    const [fields, setFields] = useState([formObject]);
    const [tableNameform, setTableNameForm] = useState("");

    useEffect(() => {
        if (isEdit) {
            initialFormData.formfields.forEach((element) => {
                for (const field in element) {
                    element[field] = element[field] ?? "";
                }
            });
            setTableNameForm(initialFormData.name);
            setFields(initialFormData.formfields);
        }
    }, []);

    const handlefieldsChange = (e, i) => {
        const ele = e.target.name;
        const newfields = [...fields];

        if (e.target.name == "default_value") {
            newfields[i][ele] =
                newfields[i]["default"] == "As Defined:" ? e.target.value : "";
        } else {
            newfields[i][ele] =
                ele == "isNull" || ele == "is_auto_increments"
                    ? e.target.checked
                    : e.target.value;
        }

        setFields(newfields);
    };

    const createModule = () => {

        const method = initialFormData.isModuleCreated ? "delete" : "post";

        router.visit("/module/" + tableId, {
            method: method,
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            onSuccess: (page) => {},
        });
    };

    const handleTableNameChange = (e) => {
        setTableNameForm(e.target.value);
    };

    const handleAddfields = () => {
        setFields([...fields, formObject]);
    };

    const handleDeletefields = (i) => {
        const newFields = [...fields];
        newFields.splice(i, 1);
        setFields(newFields);
    };

    const handleSubmit = (event) => {
        event.preventDefault();

        const url = isEdit ? `/table/${tableId}` : "/table";
        const method = isEdit ? "put" : "post";

        router.visit(url, {
            method: method,
            data: {
                name: tableNameform,
                table_fields: fields,
            },
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
        });
    };

    return (
        <div className="background-gradient min-h-screen">
            <div className="container mx-auto p-10 flex justify-center">
                <div className="w-full max-w-full">
                    <div className="bg-white rounded-lg shadow-md p-8">
                        <h2 className="text-2xl font-semibold mb-6 text-center">
                            {isEdit ? "Edit" : "Create"} Table
                        </h2>

                        <form onSubmit={handleSubmit}>
                            <div className="mb-4">
                                <label
                                    htmlFor="column_name"
                                    className="block text-sm font-medium text-gray-600"
                                >
                                    Table Name
                                </label>
                                <input
                                    type="text"
                                    id="column_name"
                                    name="column_name"
                                    className="mt-1 p-2 border rounded-md w-full"
                                    value={tableNameform}
                                    onChange={(e) => handleTableNameChange(e)}
                                />
                            </div>

                            <button
                                type="button"
                                className="bg-blue-400 text-white px-4 py-2 rounded-md w-full mb-2"
                                onClick={handleAddfields}
                            >
                                + Add Column
                            </button>

                            {fields.map((field, index) => (
                                <div
                                    className="grid grid-cols-8 gap-1 py-2"
                                    key={index}
                                >
                                    <div className="mb-4">
                                        <label
                                            htmlFor="name"
                                            className="block text-sm font-medium text-gray-600"
                                        >
                                            Name
                                        </label>
                                        <input
                                            type="text"
                                            id="name"
                                            name="name"
                                            className="mt-1 p-2 border rounded-md w-full"
                                            value={field.name}
                                            onChange={(e) =>
                                                handlefieldsChange(e, index)
                                            }
                                        />
                                    </div>
                                    <div className="mb-4">
                                        <label
                                            htmlFor="type"
                                            className="block text-sm font-medium text-gray-600"
                                        >
                                            Type
                                        </label>
                                        <select
                                            id="type"
                                            name="type"
                                            className="mt-1 p-2 border rounded-md w-full"
                                            value={field.type}
                                            onChange={(e) =>
                                                handlefieldsChange(e, index)
                                            }
                                        >
                                            {formOption.type.map(
                                                (option, index) => (
                                                    <option
                                                        value={option}
                                                        key={index}
                                                    >
                                                        {option}
                                                    </option>
                                                )
                                            )}
                                        </select>
                                    </div>
                                    <div className="mb-4">
                                        <label
                                            htmlFor="type"
                                            className="block text-sm font-medium text-gray-600"
                                        >
                                            Length/Value
                                        </label>
                                        <input
                                            type="text"
                                            id="length_value"
                                            name="length_value"
                                            className="mt-1 p-2 border rounded-md w-full"
                                            value={field.length_value}
                                            onChange={(e) =>
                                                handlefieldsChange(e, index)
                                            }
                                        />
                                    </div>
                                    <div className="mb-4">
                                        <label
                                            htmlFor="type"
                                            className="block text-sm font-medium text-gray-600"
                                        >
                                            Default
                                        </label>
                                        <select
                                            id="default"
                                            name="default"
                                            className="mt-1 p-2 border rounded-md w-full"
                                            value={field.default}
                                            onChange={(e) =>
                                                handlefieldsChange(e, index)
                                            }
                                        >
                                            {formOption.default.map(
                                                (option, index) => (
                                                    <option
                                                        value={option}
                                                        key={index}
                                                    >
                                                        {option}
                                                    </option>
                                                )
                                            )}
                                        </select>
                                        {field.default == "As Defined:" && (
                                            <input
                                                type="text"
                                                id="default_value"
                                                name="default_value"
                                                className="mt-1 p-2 border rounded-md w-full"
                                                value={field.default_value}
                                                onChange={(e) =>
                                                    handlefieldsChange(e, index)
                                                }
                                            />
                                        )}
                                    </div>
                                    <div className="mb-4">
                                        <label
                                            htmlFor="type"
                                            className="block text-sm font-medium text-gray-600"
                                        >
                                            Attributes
                                        </label>
                                        <select
                                            id="attributes"
                                            name="attributes"
                                            className="mt-1 p-2 border rounded-md w-full"
                                            value={field.attributes}
                                            onChange={(e) =>
                                                handlefieldsChange(e, index)
                                            }
                                        >
                                            <option value=""></option>
                                            {formOption.attributes.map(
                                                (option, index) => (
                                                    <option
                                                        value={option}
                                                        key={index}
                                                    >
                                                        {option}
                                                    </option>
                                                )
                                            )}
                                        </select>
                                    </div>
                                    <div className="mb-4">
                                        <label
                                            htmlFor="type"
                                            className="block text-sm font-medium text-gray-600"
                                        >
                                            Index
                                        </label>
                                        <select
                                            id="index"
                                            name="index"
                                            className="mt-1 p-2 border rounded-md w-full"
                                            value={field.index}
                                            onChange={(e) =>
                                                handlefieldsChange(e, index)
                                            }
                                        >
                                            <option value="">---</option>
                                            {formOption.index.map(
                                                (option, index) => (
                                                    <option
                                                        value={option}
                                                        key={index}
                                                    >
                                                        {option}
                                                    </option>
                                                )
                                            )}
                                        </select>
                                    </div>
                                    <div className="grid grid-cols-2 gap-1">
                                        <div className="mb-4">
                                            <label
                                                htmlFor="type"
                                                className="block text-sm font-medium text-gray-600 ml-2"
                                            >
                                                Null
                                            </label>
                                            <input
                                                type="checkbox"
                                                name="isNull"
                                                defaultChecked={field.isNull}
                                                className="form-checkbox text-blue-500 m-4"
                                                value={field.isNull}
                                                onChange={(e) =>
                                                    handlefieldsChange(e, index)
                                                }
                                            />
                                        </div>
                                    </div>
                                    <div className="mb-4 pt-6">
                                        <button
                                            type="button"
                                            className="bg-blue-500 text-white p-2 rounded-md"
                                            onClick={() =>
                                                handleDeletefields(index)
                                            }
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            ))}

                            <div className="flex items-center justify-end">
                                {tableId && (
                                    <button
                                        type="button"
                                        className="bg-red-500 text-white px-4 py-2 mr-2 rounded-md"
                                        onClick={createModule}
                                    >
                                        {initialFormData.isModuleCreated
                                            ? "Delete"
                                            : "Create"}{" "}
                                        Module
                                    </button>
                                )}
                                {tableId &&
                                    initialFormData.isModuleCreated && (
                                        <button
                                            type="button"
                                            className="bg-blue-500 text-white px-4 py-2 mr-2 rounded-md"
                                            onClick={() => router.get(`/${tableNameform}`)}
                                        >
                                            View Module
                                        </button>
                                    )}
                                <button
                                    type="submit"
                                    className="bg-blue-500 text-white px-4 py-2 rounded-md"
                                >
                                    {isEdit ? "Update" : "Create"} Table
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
}
