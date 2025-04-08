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
import { Switch } from "@/components/ui/switch.jsx";
import { Checkbox } from "@/components/ui/checkbox.jsx";

export default function Form({
    data,
    setData,
    errors,
    branches,
    roles,
    onSubmit,
}) {
    const handleRoleChange = (checked, role) => {
        const value = checked
            ? [...data.roles, role.id]
            : data.roles.filter((id) => id !== role.id);
        setData("roles", value);
    };

    return (
        <form className="w-full" onSubmit={onSubmit}>
            <div className="grid grid-cols-2 gap-4">
                <div>
                    <Label>Nom</Label>
                    <Input
                        className="w-full"
                        type="text"
                        name="lastname"
                        value={data.lastname}
                        onChange={(e) => setData("lastname", e.target.value)}
                    />
                    <InputError message={errors.lastname} className="mt-2" />
                </div>
                <div>
                    <Label>Prénom</Label>
                    <Input
                        className="w-full"
                        type="text"
                        name="firstname"
                        value={data.firstname}
                        onChange={(e) => setData("firstname", e.target.value)}
                    />
                    <InputError message={errors.firstname} className="mt-2" />
                </div>
                <div>
                    <Label>Email</Label>
                    <Input
                        className="w-full"
                        type="text"
                        name="email"
                        value={data.email}
                        onChange={(e) => setData("email", e.target.value)}
                    />
                    <InputError message={errors.email} className="mt-2" />
                </div>
                <div>
                    <Label>Branche</Label>
                    <Select
                        className="w-full"
                        defaultValue={data.branch_id}
                        onValueChange={(value) => setData("branch_id", value)}
                        name="branch_id"
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Sélectionner une branche" />
                        </SelectTrigger>
                        <SelectContent>
                            {branches.map((branch) => (
                                <SelectItem
                                    key={branch.id}
                                    value={String(branch.id)}
                                >
                                    {branch.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    <InputError message={errors.branch_id} className="mt-2" />
                </div>
                <div>
                    <Label>Jours de travail par semaine</Label>
                    <Select
                        className="w-full"
                        defaultValue={data.workdays_per_week}
                        onValueChange={(value) =>
                            setData("workdays_per_week", value)
                        }
                        name="branch_id"
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Sélectionner un nombre" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="5">5 jours</SelectItem>
                            <SelectItem value="4">4 jours</SelectItem>
                            <SelectItem value="3">3 jours</SelectItem>
                            <SelectItem value="2">2 jours</SelectItem>
                            <SelectItem value="1">1 jour</SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError
                        message={errors.workdays_per_week}
                        className="mt-2"
                    />
                </div>
                <div>
                    <Label>Ancienneté</Label>
                    <Input
                        className="w-full"
                        type="text"
                        name="seniority"
                        value={data.seniority}
                        onChange={(e) => setData("seniority", e.target.value)}
                    />
                    <InputError message={errors.seniority} className="mt-2" />
                </div>
                <div className="flex items-center">
                    <Label>Actif</Label>
                    <Switch
                        className="ml-4"
                        checked={data.is_active}
                        onCheckedChange={(value) => setData("is_active", value)}
                    ></Switch>
                    <InputError message={errors.is_active} className="mt-2" />
                </div>
                <div>
                    <Label>ID Azure</Label>
                    <Input
                        className="w-full"
                        type="text"
                        name="azure_id"
                        value={data.azure_id}
                        onChange={(e) => setData("azure_id", e.target.value)}
                    />
                    <InputError message={errors.azure_id} className="mt-2" />
                </div>
                <div>
                    {roles.map((role) => (
                        <div
                            key={role.id}
                            className="flex items-center space-x-2"
                        >
                            <Checkbox
                                name="roles[]"
                                id={`roles_${role.id}`}
                                value={role.id}
                                checked={data.roles.includes(role.id)}
                                onCheckedChange={(value) =>
                                    handleRoleChange(value, role)
                                }
                            />
                            <label htmlFor={`roles_${role.id}`}>
                                {role.name}
                            </label>
                        </div>
                    ))}
                    <InputError message={errors.roles} className="mt-2" />
                </div>
            </div>
            <div className="flex justify-end">
                <PrimaryButton>Enregistrer</PrimaryButton>
            </div>
        </form>
    );
}
