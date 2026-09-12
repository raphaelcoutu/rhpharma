import InputError from '@/components/input-error';
import PrimaryButton from '@/components/primary-button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

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
                        placeholder="Ex. Jour, soir, nuit..."
                        className="mt-2"
                        required
                    />
                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div>
                    <Label htmlFor="start_time">Début</Label>
                    <Input
                        id="start_time"
                        name="start_time"
                        type="time"
                        step="1"
                        value={data.start_time}
                        onChange={(event) => setData('start_time', event.target.value)}
                        className="mt-2"
                        required
                    />
                    <InputError message={errors.start_time} className="mt-2" />
                </div>

                <div>
                    <Label htmlFor="end_time">Fin</Label>
                    <Input
                        id="end_time"
                        name="end_time"
                        type="time"
                        step="1"
                        value={data.end_time}
                        onChange={(event) => setData('end_time', event.target.value)}
                        className="mt-2"
                        required
                    />
                    <InputError message={errors.end_time} className="mt-2" />
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
