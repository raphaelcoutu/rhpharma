import InputError from '@/components/input-error';
import PrimaryButton from '@/components/primary-button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

export default function Form({ data, setData, errors, processing, onSubmit, departments, shiftTypes, submitLabel }) {
    return (
        <form className="space-y-8" onSubmit={onSubmit}>
            <div className="grid gap-6 sm:grid-cols-2">
                <div>
                    <Label htmlFor="code">Code</Label>
                    <Input
                        id="code"
                        name="code"
                        value={data.code}
                        onChange={(event) => setData('code', event.target.value)}
                        placeholder="Ex. J, S, N..."
                        maxLength={10}
                        className="mt-2"
                        required
                    />
                    <InputError message={errors.code} className="mt-2" />
                </div>

                <div>
                    <Label htmlFor="department_id">Département</Label>
                    <Select value={String(data.department_id ?? '')} onValueChange={(value) => setData('department_id', value)}>
                        <SelectTrigger id="department_id" className="mt-2">
                            <SelectValue placeholder="Choisir un département" />
                        </SelectTrigger>
                        <SelectContent>
                            {departments.map((department) => (
                                <SelectItem key={department.id} value={String(department.id)}>
                                    {department.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    <InputError message={errors.department_id} className="mt-2" />
                </div>

                <div className="sm:col-span-2">
                    <Label htmlFor="shift_type_id">Type de shift</Label>
                    <Select value={String(data.shift_type_id ?? '')} onValueChange={(value) => setData('shift_type_id', value)}>
                        <SelectTrigger id="shift_type_id" className="mt-2">
                            <SelectValue placeholder="Choisir un type de shift" />
                        </SelectTrigger>
                        <SelectContent>
                            {shiftTypes.map((shiftType) => (
                                <SelectItem key={shiftType.id} value={String(shiftType.id)}>
                                    {shiftType.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    <InputError message={errors.shift_type_id} className="mt-2" />
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
