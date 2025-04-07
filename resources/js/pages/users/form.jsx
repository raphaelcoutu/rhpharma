import InputLabel from '@/components/input-label.jsx';
import TextInput from '@/components/text-input.jsx';
import InputError from '@/components/input-error.jsx';
import Select from '@/components/select.jsx';
import PrimaryButton from '@/components/primary-button.jsx';

export default function Form({data, setData, errors, branches, roles, onSubmit}) {

    const handleRoleChange = (e, role) => {
        const value = e.target.checked ? [...data.roles, role.id] : data.roles.filter(id => id !== role.id)
        setData('roles', value)
    };

    return <form className="w-full" onSubmit={onSubmit}>
        <div className="grid grid-cols-2 gap-4">
            <div>
                <InputLabel value="Nom"/>
                <TextInput className="w-full" type="text" name="lastname" value={data.lastname}
                           onChange={(e) => setData('lastname', e.target.value)}/>
                <InputError message={errors.lastname} className="mt-2"/>
            </div>
            <div>
                <InputLabel value="Prénom"/>
                <TextInput className="w-full" type="text" name="firstname" value={data.firstname}
                           onChange={(e) => setData('firstname', e.target.value)}/>
                <InputError message={errors.firstname} className="mt-2"/>
            </div>
            <div>
                <InputLabel value="Email"/>
                <TextInput className="w-full" type="text" name="email" value={data.email}
                           onChange={(e) => setData('email', e.target.value)}/>
                <InputError message={errors.email} className="mt-2"/>
            </div>
            <div>
                <InputLabel value="Branche"/>
                <Select className="w-full" value={data.branch_id}
                        onChange={(e) => setData('branch_id', e.target.value)} name="branch_id">
                    {branches.map((branch) => (
                        <option key={branch.id} value={branch.id}>{branch.name}</option>
                    ))}
                </Select>
                <InputError message={errors.branch_id} className="mt-2"/>
            </div>
            <div>
                <InputLabel value="Jours de travail"/>
                <Select className="w-full" value={data.workdays_per_week}
                        onChange={(e) => setData('workdays_per_week', e.target.value)}
                        name="branch_id">
                    <option value="5">5 jours</option>
                    <option value="4">4 jours</option>
                    <option value="3">3 jours</option>
                    <option value="2">2 jours</option>
                    <option value="1">1 jour</option>
                </Select>
                <InputError message={errors.workdays_per_week} className="mt-2"/>
            </div>
            <div>
                <InputLabel value="Ancienneté"/>
                <TextInput className="w-full" type="text" name="seniority" value={data.seniority}
                           onChange={(e) => setData('seniority', e.target.value)}/>
                <InputError message={errors.seniority} className="mt-2"/>
            </div>
            <div>
                <InputLabel value="Actif"/>
                <div className="flex items-center space-x-4">
                    <div className="flex items-center">
                        <input type="radio" name="is_active" checked={data.is_active === true}
                               value="true" className="mr-2" id="is_active_1"
                               onChange={(e) => setData('is_active', e.target.value === 'true')}/>
                        <label htmlFor="is_active_1" className="cursor-pointer">Oui</label>
                    </div>
                    <div className="flex items-center">
                        <input type="radio" name="is_active" checked={data.is_active === false}
                               value="false" className="mr-2" id="is_active_0"
                               onChange={(e) => setData('is_active', e.target.value === 'true')}/>
                        <label htmlFor="is_active_0" className="cursor-pointer">Non</label>
                    </div>
                </div>
                <InputError message={errors.is_active} className="mt-2"/>
            </div>
            <div>
                <InputLabel value="ID Azure"/>
                <TextInput className="w-full" type="text" name="azure_id" value={data.azure_id}
                           onChange={(e) => setData('azure_id', e.target.value)}/>
                <InputError message={errors.azure_id} className="mt-2"/>
            </div>
            <div>
                {roles.map((role) => (
                    <div key={role.id}>
                        <input type="checkbox" name="roles[]" id={`roles_${role.id}`}
                               value={role.id} className='mr-2'
                               checked={data.roles.includes(role.id)}
                               onChange={e => handleRoleChange(e, role)}/>
                        <label htmlFor={`roles_${role.id}`}>{role.name}</label>
                    </div>
                ))}
                <InputError message={errors.roles} className="mt-2"/>
            </div>
        </div>
        <div className="flex justify-end">
            <PrimaryButton>Enregistrer</PrimaryButton>
        </div>
    </form>;
}
