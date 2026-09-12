import InputError from '@/components/input-error';
import PrimaryButton from '@/components/primary-button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function Form({ data, setData, errors, processing, onSubmit }) {
    const togglePermission = (code, checked) => {
        setData('permissions', {
            ...data.permissions,
            [code]: checked,
        });
    };

    return (
        <form className="space-y-8" onSubmit={onSubmit}>
            <div className="grid gap-6 sm:grid-cols-2">
                <div>
                    <Label htmlFor="name">Nom du rôle</Label>
                    <Input
                        id="name"
                        name="name"
                        value={data.name}
                        onChange={(event) => setData('name', event.target.value)}
                        placeholder="Ex. Gestionnaire"
                        className="mt-2"
                        required
                    />
                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div>
                    <Label htmlFor="description">Description</Label>
                    <Input
                        id="description"
                        name="description"
                        value={data.description}
                        onChange={(event) => setData('description', event.target.value)}
                        placeholder="Décrivez brièvement ce rôle..."
                        className="mt-2"
                    />
                    <InputError message={errors.description} className="mt-2" />
                </div>
            </div>

            <fieldset>
                <legend className="text-sm font-medium text-gray-900">Permissions</legend>
                <p className="mt-1 text-sm text-gray-500">Sélectionnez les accès accordés à ce rôle.</p>
                <div className="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    {Object.keys(data.permissions).map((code) => {
                        const inputId = `permission-${code}`;

                        return (
                            <label
                                key={code}
                                htmlFor={inputId}
                                className="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-4 transition hover:border-indigo-300 hover:bg-indigo-50/40"
                            >
                                <Checkbox
                                    id={inputId}
                                    checked={Boolean(data.permissions[code])}
                                    onCheckedChange={(checked) => togglePermission(code, checked === true)}
                                />
                                <span className="font-mono text-sm text-gray-700">{code}</span>
                            </label>
                        );
                    })}
                </div>
                <InputError message={errors.permissions} className="mt-2" />
            </fieldset>

            <div className="flex justify-end border-t border-gray-100 pt-6">
                <PrimaryButton type="submit" disabled={processing}>
                    {processing ? 'Enregistrement...' : 'Enregistrer les modifications'}
                </PrimaryButton>
            </div>
        </form>
    );
}
