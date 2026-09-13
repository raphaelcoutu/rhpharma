import { useMemo } from 'react';

function dateKey(value) {
    return String(value ?? '').slice(0, 10);
}

function constraintCode(constraint) {
    const code = constraint.constraint_type?.code ?? constraint.code;

    if (!code) {
        return null;
    }

    return Number(constraint.weight) === 1 ? `[${code}]` : code;
}

function isConstraintActive(constraint) {
    const status = Number(constraint.constraint_type?.status ?? 0);

    return status === 2 || (status === 1 && Number(constraint.weight) === 1);
}

export function useCalendarData(users = [], assignedShifts = []) {
    const shiftsByUserDate = useMemo(() => {
        const map = {};

        assignedShifts.forEach((assignedShift) => {
            const userId = assignedShift.user_id;
            const date = dateKey(assignedShift.date);

            if (!userId || !date) {
                return;
            }

            map[userId] ??= {};
            map[userId][date] ??= [];
            map[userId][date].push(assignedShift);
        });

        return map;
    }, [assignedShifts]);

    const constraintsByUserDate = useMemo(() => {
        const map = {};

        users.forEach((user) => {
            (user.constraints ?? []).forEach((constraint) => {
                const startDate = dateKey(constraint.start_datetime);
                const endDate = dateKey(constraint.end_datetime);

                if (!startDate || !endDate) {
                    return;
                }

                const currentDate = new Date(`${startDate}T12:00:00`);
                const lastDate = new Date(`${endDate}T12:00:00`);

                while (currentDate <= lastDate) {
                    const date = currentDate.toISOString().slice(0, 10);

                    if (constraint.day === null || constraint.day === undefined || Number(constraint.day) === currentDate.getDay()) {
                        map[user.id] ??= {};
                        map[user.id][date] ??= [];
                        map[user.id][date].push({
                            ...constraint,
                            code: constraintCode(constraint),
                            isActive: isConstraintActive(constraint),
                        });
                    }

                    currentDate.setDate(currentDate.getDate() + 1);
                }
            });
        });

        Object.values(map).forEach((dates) => {
            Object.values(dates).forEach((constraints) => {
                constraints.sort((first, second) => Number(second.isActive) - Number(first.isActive));
            });
        });

        return map;
    }, [users]);

    return { shiftsByUserDate, constraintsByUserDate };
}

export function getDateKey(value) {
    return dateKey(value);
}
