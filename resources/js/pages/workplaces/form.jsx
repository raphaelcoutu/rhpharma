import InputError from '@/components/input-error';
import PrimaryButton from '@/components/primary-button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const fields = [
    { name: 'name', label: 'Nom du lieu', placeholder: 'Ex. CHUS - Hôpital Fleurimont', className: 'md:col-span-2' },
    { name: 'code', label: 'Code', placeholder: 'Ex. HF' },
    { name: 'address', label: 'Adresse', placeholder: 'Ex. 12e Avenue Nord' },
    { name: 'city', label: 'Ville', placeholder: 'Ex. Sherbrooke' },
    { name: 'province', label: 'Province', placeholder: 'Ex. Québec' },
    { name: 'country', label: 'Pays', placeholder: 'Ex. Canada' },
    { name: 'postal_code', label: 'Code postal', placeholder: 'Ex. J1J 1J1' },
];

export default function Form({ data, setData, errors, processing, onSubmit }) {
    return (
        <form className="space-y-8" onSubmit={onSubmit}>
            <div className="grid gap-6 md:grid-cols-2">
                {fields.map((field) => (
                    <div key={field.name} className={field.className}>
                        <Label htmlFor={field.name}>{field.label}</Label>
                        <Input
                            id={field.name}
                            name={field.name}
                            value={data[field.name]}
                            onChange={(event) => setData(field.name, event.target.value)}
                            placeholder={field.placeholder}
                            className="mt-2"
                            required
                        />
                        <InputError message={errors[field.name]} className="mt-2" />
                    </div>
                ))}
            </div>

            <div className="flex justify-end border-t border-gray-100 pt-6">
                <PrimaryButton type="submit" disabled={processing}>
                    {processing ? 'Enregistrement...' : 'Enregistrer le lieu'}
                </PrimaryButton>
            </div>
        </form>
    );
}
