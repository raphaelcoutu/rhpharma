import InputError from "@/components/input-error.jsx";
import PrimaryButton from "@/components/primary-button.jsx";
import { Label } from "@/components/ui/label.jsx";
import { Input } from "@/components/ui/input.jsx";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select.jsx";

export default function Form({
    data,
    setData,
    errors,
    onSubmit,
    departmentTypes,
    workplaces,
}) {
    return (
        <form className="w-full" onSubmit={onSubmit}>
            <div className="flex flex-col gap-2">
                <div className="flex">
                    <div className="w-1/4">
                        <Label>Nom</Label>
                    </div>

                    <div className="w-3/4">
                        <Input
                            className="w-full"
                            type="text"
                            name="name"
                            value={data.name}
                            onChange={(e) => setData("name", e.target.value)}
                        />
                        <InputError message={errors.name} className="mt-2" />
                    </div>
                </div>
                <div className="flex">
                    <div className="w-1/4">
                        <Label>Description</Label>
                    </div>

                    <div className="w-3/4">
                        <Input
                            className="w-full"
                            type="text"
                            name="description"
                            value={data.description}
                            onChange={(e) =>
                                setData("description", e.target.value)
                            }
                        />
                        <InputError
                            message={errors.description}
                            className="mt-2"
                        />
                    </div>
                </div>
                <div className="flex">
                    <div className="w-1/4">
                        <Label>Type</Label>
                    </div>

                    <div className="w-3/4">
                        <Select
                            className="w-full"
                            name="department_type_id"
                            defaultValue={data.department_type_id}
                            onValueChange={(value) =>
                                setData("department_type_id", value)
                            }
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="-- Choisir --" />
                            </SelectTrigger>
                            <SelectContent>
                                {departmentTypes.map((type) => (
                                    <SelectItem
                                        key={type.id}
                                        value={String(type.id)}
                                    >
                                        {type.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <InputError
                            message={errors.department_type_id}
                            className="mt-2"
                        />
                    </div>
                </div>
                <div className="flex">
                    <div className="w-1/4">
                        <Label>Lieu de travail</Label>
                    </div>

                    <div className="w-3/4">
                        <Select
                            className="w-full"
                            name="workplace_id"
                            defaultValue={data.workplace_id}
                            onValueChange={(value) =>
                                setData("workplace_id", value)
                            }
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="-- Choisir --" />
                            </SelectTrigger>
                            <SelectContent>
                                {workplaces.map((type) => (
                                    <SelectItem
                                        key={type.id}
                                        value={String(type.id)}
                                    >
                                        {type.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <InputError
                            message={errors.workplace_id}
                            className="mt-2"
                        />
                    </div>
                </div>
            </div>
            <div className="flex justify-end mt-2">
                <PrimaryButton>Enregistrer</PrimaryButton>
            </div>
        </form>
    );
}
