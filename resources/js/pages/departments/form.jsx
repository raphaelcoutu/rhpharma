import TextInput from '@/components/text-input.jsx';
import InputError from '@/components/input-error.jsx';
import PrimaryButton from '@/components/primary-button.jsx';
import InputLabel from "@/components/input-label.jsx";
import Select from "@/components/select.jsx";

export default function Form({data, setData, errors, onSubmit, departmentTypes, workplaces}) {

    return <form className="w-full" onSubmit={ onSubmit }>
        <div className='flex flex-col gap-2'>
            <div className='flex'>
                <div className='w-1/4'>
                    <InputLabel value="Nom"/>
                </div>

                <div className='w-3/4'>
                    <TextInput className="w-full" type="text" name="name" value={ data.name }
                               onChange={ (e) => setData('name', e.target.value) }/>
                    <InputError message={ errors.name } className="mt-2"/>
                </div>
            </div>
            <div className='flex'>
                <div className='w-1/4'>
                    <InputLabel value="Description"/>
                </div>

                <div className='w-3/4'>
                    <TextInput className="w-full" type="text" name="description" value={ data.description }
                               onChange={ (e) => setData('description', e.target.value) }/>
                    <InputError message={ errors.description } className="mt-2"/>
                </div>
            </div>
            <div className='flex'>
                <div className='w-1/4'>
                    <InputLabel value="Type"/>
                </div>

                <div className='w-3/4'>
                    <Select className='w-full' name='department_type_id' value={ data.department_type_id } onChange={ (e) => setData('department_type_id', e.target.value) }>
                        <option value='' selected disabled>-- Choisir --</option>
                        {departmentTypes.map((type) => <option key={ type.id } value={ type.id }>{ type.name }</option>)}
                    </Select>
                    <InputError message={ errors.department_type_id } className="mt-2"/>
                </div>
            </div>
            <div className='flex'>
                <div className='w-1/4'>
                    <InputLabel value="Lieu de travail"/>
                </div>

                <div className='w-3/4'>
                    <Select className='w-full' name='workplace_id' value={ data.workplace_id } onChange={ (e) => setData('workplace_id', e.target.value) }>
                        <option value='' selected disabled>-- Choisir --</option>
                        {workplaces.map((type) => <option key={ type.id } value={ type.id }>{ type.name }</option>)}
                    </Select>
                    <InputError message={ errors.workplace_id } className="mt-2"/>
                </div>
            </div>
        </div>
        <div className="flex justify-end mt-2">
            <PrimaryButton>Enregistrer</PrimaryButton>
        </div>
    </form>;
}
