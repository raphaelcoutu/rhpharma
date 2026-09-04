import InputError from '@/components/input-error';
import PrimaryButton from '@/components/primary-button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const booleanFields = [
    {
        name: 'is_work',
        label: 'Travail',
        description: 'Cette contrainte concerne-t-elle une journée travaillée ?',
    },
    {
        name: 'is_single_day',
        label: 'Une seule journée',
        description: 'La contrainte s’applique-t-elle sur une seule journée ?',
    },
    {
        name: 'is_group_constraint',
        label: 'Selon disponibilité',
        description: 'La contrainte dépend-elle de la disponibilité du groupe ?',
    },
    {
        name: 'is_day_in_schedule',
        label: 'Journée à l’horaire',
        description: 'La journée doit-elle apparaître dans l’horaire ?',
    },
];

function BooleanField({ data, errors, setData, field }) {
    return (
        <fieldset className="rounded-lg border border-gray-200 p-4">
            <legend className="px-1 text-sm font-medium text-gray-900">{field.label}</legend>
            <p className="mt-1 text-sm text-gray-500">{field.description}</p>
            <div className="mt-4 flex flex-wrap gap-3">
                {[
                    ['1', 'Oui'],
                    ['0', 'Non'],
                ].map(([value, label]) => {
                    const id = `${field.name}-${value}`;

                    return (
                        <label key={value} htmlFor={id} className="cursor-pointer">
                            <input
                                id={id}
                                name={field.name}
                                type="radio"
                                value={value}
                                checked={String(data[field.name]) === value}
                                onChange={(event) => setData(field.name, event.target.value)}
                                className="peer sr-only"
                                required
                            />
                            <span className="inline-flex min-w-16 justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 peer-focus-visible:ring-2 peer-focus-visible:ring-indigo-500">
                                {label}
                            </span>
                        </label>
                    );
                })}
            </div>
            <InputError message={errors[field.name]} className="mt-2" />
        </fieldset>
    );
}

export default function Form({ data, setData, errors, processing, onSubmit, submitLabel }) {
    return (
        <form className="space-y-8" onSubmit={onSubmit}>
            <div className="grid gap-6 sm:grid-cols-2">
                <div className="sm:col-span-2">
                    <Label htmlFor="name">Nom du type</Label>
                    <Input
                        id="name"
                        name="name"
                        value={data.name}
                        onChange={(event) => setData('name', event.target.value)}
                        placeholder="Ex. Travail de jour, repos consécutifs..."
                        className="mt-2"
                        required
                    />
                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div className="sm:col-span-2">
                    <Label htmlFor="description">Description</Label>
                    <Input
                        id="description"
                        name="description"
                        value={data.description}
                        onChange={(event) => setData('description', event.target.value)}
                        placeholder="Décrivez brièvement la règle..."
                        className="mt-2"
                    />
                    <InputError message={errors.description} className="mt-2" />
                </div>

                <div>
                    <Label htmlFor="code">Code</Label>
                    <Input
                        id="code"
                        name="code"
                        value={data.code}
                        onChange={(event) => setData('code', event.target.value)}
                        placeholder="Ex. TRV"
                        maxLength={5}
                        className="mt-2 font-mono uppercase"
                        required
                    />
                    <InputError message={errors.code} className="mt-2" />
                </div>

                <div>
                    <Label htmlFor="azure_id">ID Azure</Label>
                    <Input
                        id="azure_id"
                        name="azure_id"
                        type="number"
                        value={data.azure_id}
                        onChange={(event) => setData('azure_id', event.target.value)}
                        placeholder="Optionnel"
                        className="mt-2"
                    />
                    <InputError message={errors.azure_id} className="mt-2" />
                </div>
            </div>

            <div className="grid gap-4 sm:grid-cols-2">
                {booleanFields.map((field) => (
                    <BooleanField key={field.name} data={data} errors={errors} setData={setData} field={field} />
                ))}
            </div>

            <div className="flex justify-end border-t border-gray-100 pt-6">
                <PrimaryButton type="submit" disabled={processing}>
                    {processing ? 'Enregistrement...' : submitLabel}
                </PrimaryButton>
            </div>
        </form>
    );
}
