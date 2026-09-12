import ApplicationLogo from '@/components/application-logo.jsx';
import Dropdown from '@/components/dropdown.jsx';
import NavLink from '@/components/nav-link.jsx';
import ResponsiveNavLink from '@/components/responsive-nav-link.jsx';
import { Link, usePage } from '@inertiajs/react';
import { home, logout } from '@/routes';
import { index as branchesIndex } from '@/routes/branches';
import { index as constraintTypesIndex } from '@/routes/constraintTypes';
import { index as departmentsIndex } from '@/routes/departments';
import { index as holidaysIndex } from '@/routes/holidays';
import { edit as profileEdit } from '@/routes/profile';
import { index as rolesIndex } from '@/routes/roles';
import { index as schedulesIndex } from '@/routes/schedules';
import { index as constraintsValidatorIndex } from '@/routes/constraintsValidator';
import { index as shiftTypesIndex } from '@/routes/shiftTypes';
import { index as usersIndex } from '@/routes/users';
import { index as workplacesIndex } from '@/routes/workplaces';
import { ChevronDown } from 'lucide-react';
import { useState } from 'react';

const configurationLinks = [
    { label: 'Branches', href: branchesIndex(), pathPrefix: '/branches' },
    { label: 'Rôles et permissions', href: rolesIndex(), pathPrefix: '/roles' },
    { label: 'Secteurs', href: departmentsIndex(), pathPrefix: '/departments' },
    { label: 'Lieux de travail', href: workplacesIndex(), pathPrefix: '/workplaces' },
    { label: 'Types de shifts', href: shiftTypesIndex(), pathPrefix: '/shift-types' },
    { label: 'Jours fériés', href: holidaysIndex(), pathPrefix: '/holidays' },
    { label: 'Types de contraintes', href: constraintTypesIndex(), pathPrefix: '/constraint-types' },
];

export default function AuthenticatedLayout({ header, children }) {
    const { props, url } = usePage();
    const user = props.auth.user;
    const currentPath = url.split('?')[0];

    const isActive = (pathPrefix, exact = false) => {
        return exact ? currentPath === pathPrefix : currentPath === pathPrefix || currentPath.startsWith(`${pathPrefix}/`);
    };

    const [showingNavigationDropdown, setShowingNavigationDropdown] = useState(false);
    const configurationIsActive = configurationLinks.some(({ pathPrefix }) => isActive(pathPrefix));

    return (
        <div className="min-h-screen bg-gray-100">
            <nav className="border-b border-gray-100 bg-white">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex h-16 justify-between">
                        <div className="flex">
                            <div className="flex shrink-0 items-center">
                                <Link href="/">
                                    <ApplicationLogo className="block h-9 w-auto fill-current text-gray-800" />
                                </Link>
                            </div>

                            <div className="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink href={home()} active={isActive('/', true)}>
                                    Accueil
                                </NavLink>
                                <NavLink href={usersIndex()} active={isActive('/users', true)}>
                                    Utilisateurs
                                </NavLink>
                                <NavLink href={schedulesIndex()} active={isActive('/schedules')}>
                                    Horaires
                                </NavLink>
                                <NavLink href={constraintsValidatorIndex()} active={isActive('/constraints-validator')}>
                                    Validation
                                </NavLink>
                                <Dropdown>
                                    <Dropdown.Trigger className="flex h-full">
                                        <button
                                            type="button"
                                            aria-haspopup="menu"
                                            className={
                                                'inline-flex h-full items-center gap-1 border-b-2 px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none ' +
                                                (configurationIsActive
                                                    ? 'border-indigo-400 text-gray-900 focus:border-indigo-700'
                                                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 focus:border-gray-300 focus:text-gray-700')
                                            }
                                        >
                                            Configuration
                                            <ChevronDown className="h-4 w-4" aria-hidden="true" />
                                        </button>
                                    </Dropdown.Trigger>

                                    <Dropdown.Content align="left">
                                        {configurationLinks.map(({ label, href, pathPrefix }) => (
                                            <Dropdown.Link
                                                key={pathPrefix}
                                                href={href}
                                                className={isActive(pathPrefix) ? 'bg-indigo-50 text-indigo-700' : ''}
                                            >
                                                {label}
                                            </Dropdown.Link>
                                        ))}
                                    </Dropdown.Content>
                                </Dropdown>
                            </div>
                        </div>

                        <div className="hidden sm:ms-6 sm:flex sm:items-center">
                            <div className="rounded border p-1 text-sm">{user.branch.name}</div>
                            <div className="relative ms-3">
                                <Dropdown>
                                    <Dropdown.Trigger>
                                        <span className="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                className="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                            >
                                                {user.firstname} {user.lastname}
                                                <svg
                                                    className="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fillRule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clipRule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </Dropdown.Trigger>

                                    <Dropdown.Content>
                                        <Dropdown.Link href={profileEdit()}>Profile</Dropdown.Link>
                                        <Dropdown.Link href={logout()} method="post" as="button">
                                            Log Out
                                        </Dropdown.Link>
                                    </Dropdown.Content>
                                </Dropdown>
                            </div>
                        </div>

                        <div className="-me-2 flex items-center sm:hidden">
                            <button
                                onClick={() => setShowingNavigationDropdown((previousState) => !previousState)}
                                className="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        className={!showingNavigationDropdown ? 'inline-flex' : 'hidden'}
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        className={showingNavigationDropdown ? 'inline-flex' : 'hidden'}
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div className={(showingNavigationDropdown ? 'block' : 'hidden') + ' sm:hidden'}>
                    <div className="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink href={home()} active={isActive('/', true)}>
                            Accueil
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={usersIndex()} active={isActive('/users', true)}>
                            Utilisateurs
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={schedulesIndex()} active={isActive('/schedules')}>
                            Horaires
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={constraintsValidatorIndex()} active={isActive('/constraints-validator')}>
                            Validation
                        </ResponsiveNavLink>
                        <div className="border-t border-gray-200 pb-1 pt-4">
                            <p className="px-4 text-xs font-semibold uppercase tracking-wider text-gray-400">Configuration</p>
                            <div className="mt-1 space-y-1">
                                {configurationLinks.map(({ label, href, pathPrefix }) => (
                                    <ResponsiveNavLink key={pathPrefix} href={href} active={isActive(pathPrefix)}>
                                        {label}
                                    </ResponsiveNavLink>
                                ))}
                            </div>
                        </div>
                    </div>

                    <div className="border-t border-gray-200 pb-1 pt-4">
                        <div className="px-4">
                            <div className="text-base font-medium text-gray-800">
                                {user.firstname} {user.lastname}
                            </div>
                            <div className="text-sm font-medium text-gray-500">{user.email}</div>
                        </div>

                        <div className="mt-3 space-y-1">
                            <ResponsiveNavLink href={profileEdit()}>Profile</ResponsiveNavLink>
                            <ResponsiveNavLink method="post" href={logout()} as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            {header && (
                <header className="bg-white shadow">
                    <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">{header}</div>
                </header>
            )}

            <main>{children}</main>
        </div>
    );
}
