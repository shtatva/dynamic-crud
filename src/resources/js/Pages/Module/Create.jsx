import { useEffect, useState } from "react";
import CreateModuleForm from "../Components/CreateModuleForm";
import { router } from "@inertiajs/react";

export default function Create({
    tableFields,
    tableName,
    isEdit = false,
    initialFormData = null,
}) {
    const formObject = {};
    tableFields.forEach((element) => {
        formObject[element.name] = "";
    });

    const [isFormOnceSubmit, setIsFormOnceSubmit] = useState(false);
    const [formData, setFormData] = useState(formObject);
    const [validation, setValidation] = useState(formObject);

    useEffect(() => {
        if (isEdit) {
            setIsFormOnceSubmit(true);
            setFormData(initialFormData);
        }
    }, []);

    const validateForm = (formdata) => {
        let valid = true;
        const newErrors = { ...validation };

        tableFields.forEach((element) => {
            if (!element.isNull) {
                if (formdata[element.name] == "") {
                    newErrors[element.name] = `${element.name} is required`;
                    valid = false;
                } else {
                    newErrors[element.name] = "";
                }
            }
        });

        setValidation(newErrors);
        return valid;
    };

    const handleSubmit = (event) => {
        event.preventDefault();
        setIsFormOnceSubmit(true);
        const isValidateForm = validateForm(formData);

        if (isValidateForm) {
            const url = isEdit
                ? `/${tableName}/${initialFormData.id}`
                : `/${tableName}`;
            const method = isEdit ? "put" : "post";
            router.visit(url, {
                method: method,
                data: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            });
        }
    };

    const handleInputChange = (event) => {
        const { name, value } = event.target;
        setFormData((prevProps) => {
            const updatedData = {
                ...prevProps,
                [name]: value,
            };

            if(isFormOnceSubmit)
                validateForm(updatedData);

            return updatedData
        });
    };

    return (
        <div className="background-gradient min-h-screen">
            <div className="container mx-auto p-10 flex justify-center">
                <div className="w-full max-w-full">
                    <div className="bg-white rounded-lg shadow-md p-8">
                        <form onSubmit={handleSubmit}>
                            <div className="grid grid-cols-2">
                                {tableFields.map((field, index) => (
                                    <CreateModuleForm
                                        name={field.name}
                                        input_type={field.input_type}
                                        form_type={field.form_type}
                                        handleInputChange={handleInputChange}
                                        formData={formData}
                                        validation={validation}
                                        key={index}
                                    />
                                ))}
                            </div>

                            <div className="flex items-center justify-end">
                                <button
                                    type="submit"
                                    className="bg-blue-500 text-white px-4 py-2 rounded-md"
                                >
                                    {isEdit ? "Update" : "Create"} {tableName}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
}
