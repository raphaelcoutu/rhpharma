import InputError from '@/components/input-error';
import PrimaryButton from '@/components/primary-button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

function DateField({ data, errors, name, label, setData }) {
    return (
        <div className="grid gap-2">
            <Label htmlFor={name}>{label}</Label>
            <Input id={name} name={name} type="date" value={data[name]} onChange={(event) => setData(name, event.target.value)} required />
            <InputError message={errors[name]} />
        </div>
    );
}

export default function Form({ data, errors, onSubmit, processing, setData, submitLabel }) {
    return (
        <form className="space-y-8" onSubmit={onSubmit}>
            <div className="grid gap-6">
                <div className="grid gap-2">
                    <Label htmlFor="name">Nom de l&apos;horaire</Label>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        value={data.name}
                        onChange={(event) => setData('name', event.target.value)}
                        placeholder="Ex. Horaire d'automne"
                        autoFocus
                        required
                    />
                    <InputError message={errors.name} />
                </div>

                <div className="grid gap-6 sm:grid-cols-2">
                    <DateField data={data} errors={errors} name="limit_date_weekends" label="Date limite (fins de semaine)" setData={setData} />
                    <DateField data={data} errors={errors} name="limit_date" label="Date limite pour les contraintes" setData={setData} />
                    <DateField data={data} errors={errors} name="start_date" label="Début" setData={setData} />
                    <DateField data={data} errors={errors} name="end_date" label="Fin" setData={setData} />
                </div>
            </div>

            <div className="flex justify-end border-t border-gray-100 pt-6">
                <PrimaryButton type="submit" disabled={processing}>
                    {processing ? 'Enregistrement...' : submitLabel}
                </PrimaryButton>
            </div>
        </form>
    );
}
