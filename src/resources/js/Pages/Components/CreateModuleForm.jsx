export default function CreateModuleForm({
    form_type,
    input_type = "",
    name,
    handleInputChange,
    formData,
    validation,
}) {
    const captalizeFLetter = (string) => {
        return string[0].toUpperCase() + string.slice(1);
    };

    return (
        <div className="mb-4 px-4">
            <label
                htmlFor={name}
                className="block text-sm font-medium text-gray-600"
            >
                {captalizeFLetter(name)}
            </label>
            {form_type == "input" && (
                <>
                    <input
                        type={input_type}
                        name={name}
                        className="mt-1 p-2 border rounded-md w-full"
                        value={formData[name]}
                        onChange={(e) => handleInputChange(e)}
                    />
                    <span className="text-red-500">{validation[name]}</span>
                </>
            )}

            {form_type == "select" && (
                <select
                    name={name}
                    className="mt-1 p-2 border rounded-md w-full"
                    value={formData[name]}
                    onChange={(e) => handleInputChange(e)}
                >
                    <option value="test">Test</option>
                </select>
            )}

            {form_type == "textarea" && (
                <>
                    <textarea
                        name={name}
                        rows="4"
                        className="mt-1 p-2 border rounded-md w-full"
                        value={formData[name]}
                        onChange={(e) => handleInputChange(e)}
                    ></textarea>
                    <span className="text-red-500">{validation[name]}</span>
                </>
            )}
        </div>
    );
}
